<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTrafficSource
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('utm_source') || ! session()->has('traffic_source')) {

            $source = 'direct';

            if ($request->has('utm_source')) {
                $source = $request->input('utm_source');
            } elseif ($referer = $request->header('referer')) {
                $host = parse_url($referer, PHP_URL_HOST) ?? '';

                if (str_contains($host, 'google.')) {
                    $source = 'organic_google';
                } elseif (str_contains($host, 'bing.')) {
                    $source = 'organic_bing';
                } elseif (str_contains($host, 'facebook.') || str_contains($host, 'fb.')) {
                    $source = 'facebook';
                } elseif (str_contains($host, 'craigslist.')) {
                    $source = 'craigslist';
                } elseif (str_contains($host, 'yelp.')) {
                    $source = 'yelp';
                } elseif (str_contains($host, 'youtube.')) {
                    $source = 'youtube';
                } else {
                    $source = 'referral';
                }
            }

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
