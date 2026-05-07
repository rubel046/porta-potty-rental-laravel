<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Geo-redirect homepage visitors to their nearest city service page.
 *
 * DESIGN NOTES
 * ------------
 * Earlier version made a blocking external call to ipapi.co on the first request
 * per IP — 5-second timeout + a 302 redirect on the LCP path. That's a
 * conversion killer and an ipapi.co quota risk at scale.
 *
 * New behavior (in priority order):
 *   1. Check GEO_REDIRECT_ENABLED env flag — disabled by default. Has to be
 *      explicitly turned on.
 *   2. Prefer Cloudflare-provided headers (CF-IPCountry, CF-IPCity). Free,
 *      zero-latency, no external API. Requires the site behind Cloudflare.
 *   3. Fall back to ipapi.co ONLY if FEATURE_IPAPI_FALLBACK=true. Off by default.
 *      Still cached aggressively.
 *   4. Any missing data => pass through. Never block.
 */
class RedirectToCity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('/') || $request->ajax() || $request->wantsJson()) {
            return $next($request);
        }

        if (! filter_var(env('GEO_REDIRECT_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) {
            return $next($request);
        }

        $ip = $request->ip();
        if (in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'])) {
            return $next($request);
        }

        if (Cache::has('geo_redirect_done_'.$ip)) {
            return $next($request);
        }

        try {
            $geo = $this->resolveGeoFromCloudflare($request)
                ?? $this->resolveGeoFromIpApi($ip);

            if (! $geo) {
                return $next($request);
            }

            $city = $this->findMatchingCity($geo['city'], $geo['state_code']);
            if (! $city) {
                return $next($request);
            }

            Cache::put('geo_redirect_done_'.$ip, $city->id, now()->addDays(7));

            $servicePage = $city->servicePages()
                ->where('service_type', 'general')
                ->where('is_published', true)
                ->first();

            if ($servicePage) {
                session(['geo_detected' => true]);

                return redirect()->to($servicePage->slug, 302);
            }
        } catch (\Throwable $e) {
            Log::warning('Geo redirect failed', ['msg' => $e->getMessage()]);
        }

        return $next($request);
    }

    /**
     * Preferred: Cloudflare populates CF-IPCountry and CF-IPCity on all requests
     * when the site is behind CF. Zero latency, always-cached at edge.
     */
    private function resolveGeoFromCloudflare(Request $request): ?array
    {
        $country = $request->header('CF-IPCountry');
        $city = $request->header('CF-IPCity');
        $region = $request->header('CF-Region-Code'); // some CF plans

        if ($country !== 'US' || ! $city) {
            return null;
        }

        return [
            'city' => ucwords(strtolower($city)),
            'state_code' => strtoupper($region ?? ''),
        ];
    }

    /**
     * Legacy fallback: ipapi.co. OFF by default. Free tier is rate-limited to
     * ~1000/day and makes a blocking external HTTP call. Only use if you've
     * explicitly chosen to and accepted the risk.
     */
    private function resolveGeoFromIpApi(string $ip): ?array
    {
        if (! filter_var(env('FEATURE_IPAPI_FALLBACK', false), FILTER_VALIDATE_BOOLEAN)) {
            return null;
        }

        $data = Cache::remember('geo_'.$ip, now()->addDays(7), function () use ($ip) {
            try {
                $ctx = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'timeout' => 2, // tight timeout: never block LCP longer than this
                        'header' => "User-Agent: Laravel/1.0\r\n",
                    ],
                ]);
                $response = @file_get_contents('https://ipapi.co/'.$ip.'/json/', false, $ctx);
                if ($response === false) {
                    return null;
                }
                $decoded = json_decode($response, true);

                return is_array($decoded) ? $decoded : null;
            } catch (\Throwable $e) {
                return null;
            }
        });

        if (! $data || ($data['country_code'] ?? null) !== 'US') {
            return null;
        }

        $city = $data['city'] ?? null;
        $state = $data['region_code'] ?? null;
        if (! $city || ! $state) {
            return null;
        }

        return [
            'city' => ucwords(strtolower($city)),
            'state_code' => strtoupper($state),
        ];
    }

    private function findMatchingCity(string $cityName, string $stateCode): ?City
    {
        if ($stateCode === '') {
            return null;
        }

        return Cache::remember('geo_city_'.$cityName.'_'.$stateCode, 86400, function () use ($cityName, $stateCode) {
            return City::whereHas('state', fn ($q) => $q->where('code', $stateCode))
                ->where(function ($q) use ($cityName) {
                    $q->where('name', $cityName)->orWhere('name', 'like', $cityName.'%');
                })
                ->where('is_active', true)
                ->with(['servicePages' => fn ($q) => $q->where('service_type', 'general')->where('is_published', true)])
                ->first();
        });
    }
}
