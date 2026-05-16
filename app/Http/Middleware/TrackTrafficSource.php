<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTrafficSource
{
    public function handle(Request $request, Closure $next): Response
    {
        $source = session('traffic_source', 'direct');
        $shouldUpdate = false;

        if ($request->has('utm_source')) {
            $source = $request->input('utm_source');
            $shouldUpdate = true;
        } elseif ($referer = $request->header('referer')) {
            $host = parse_url($referer, PHP_URL_HOST) ?? '';

            $organicSource = null;
            if (str_contains($host, 'google.')) {
                $organicSource = 'organic_google';
            } elseif (str_contains($host, 'bing.')) {
                $organicSource = 'organic_bing';
            } elseif (str_contains($host, 'facebook.') || str_contains($host, 'fb.')) {
                $organicSource = 'facebook';
            } elseif (str_contains($host, 'craigslist.')) {
                $organicSource = 'craigslist';
            } elseif (str_contains($host, 'yelp.')) {
                $organicSource = 'yelp';
            } elseif (str_contains($host, 'youtube.')) {
                $organicSource = 'youtube';
            } else {
                $organicSource = 'referral';
            }

            if ($organicSource && $source === 'direct') {
                $source = $organicSource;
                $shouldUpdate = true;
            }
        }

        if ($shouldUpdate || ! session()->has('traffic_source')) {
            session([
                'traffic_source' => $source,
                'utm_source' => $request->input('utm_source'),
                'utm_medium' => $request->input('utm_medium'),
                'utm_campaign' => $request->input('utm_campaign'),
                'landing_page' => $request->path(),
                'referer' => $request->header('referer'),
            ]);
        }

        return $next($request);
    }
}
