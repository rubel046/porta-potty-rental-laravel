<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectToCity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('/') || $request->ajax() || $request->wantsJson()) {
            return $next($request);
        }

        if (Cache::has('geo_redirect_done_'.$request->ip())) {
            return $next($request);
        }

        try {
            $ip = $request->ip();

            if (in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'])) {
                return $next($request);
            }

            $geoData = $this->getGeoData($ip);

            if (! $geoData || ! isset($geoData['country_code']) || $geoData['country_code'] !== 'US') {
                return $next($request);
            }

            $stateCode = strtoupper($geoData['region_code'] ?? '');
            $cityName = ucwords(strtolower($geoData['city'] ?? ''));

            if (! $stateCode || ! $cityName) {
                return $next($request);
            }

            $city = $this->findMatchingCity($cityName, $stateCode);

            if ($city) {
                Cache::put('geo_redirect_done_'.$request->ip(), $city->id, now()->addDays(7));

                $servicePage = $city->servicePages()
                    ->where('service_type', 'general')
                    ->where('is_published', true)
                    ->first();

                if ($servicePage) {
                    return redirect()->to($servicePage->slug, 302);
                }
            }
        } catch (\Exception $e) {
            Log::error('Geo redirect failed: '.$e->getMessage());
        }

        return $next($request);
    }

    private function getGeoData(string $ip): ?array
    {
        $cacheKey = 'geo_'.$ip;

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($ip) {
            try {
                $response = file_get_contents('https://ipapi.co/'.$ip.'/json/');
                $data = json_decode($response, true);

                if ($data && isset($data['country_code'])) {
                    return $data;
                }
            } catch (\Exception $e) {
                Log::warning('IP geolocation lookup failed: '.$e->getMessage());
            }

            return null;
        });
    }

    private function findMatchingCity(string $cityName, string $stateCode): ?City
    {
        return Cache::remember('geo_city_'.$cityName.'_'.$stateCode, 86400, function () use ($cityName, $stateCode) {
            return City::whereHas('state', function ($q) use ($stateCode) {
                $q->where('code', $stateCode);
            })
                ->where(function ($q) use ($cityName) {
                    $q->where('name', $cityName)
                        ->orWhere('name', 'like', $cityName.'%');
                })
                ->where('is_active', true)
                ->with(['servicePages' => fn ($q) => $q->where('service_type', 'general')->where('is_published', true)])
                ->first();
        });
    }
}
