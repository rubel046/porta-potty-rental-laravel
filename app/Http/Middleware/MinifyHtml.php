<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            if ($content) {
                $placeholders = [];

                $content = preg_replace_callback('/<(pre|code|textarea|script|style)[^>]*>.*?<\/\1>/si', function ($m) use (&$placeholders) {
                    $key = "\x00SKIP\x00" . count($placeholders) . "\x00";
                    $placeholders[$key] = $m[0];
                    return $key;
                }, $content);

                $content = preg_replace('/\s+/', ' ', $content);
                $content = preg_replace('/> </', '><', $content);
                $content = preg_replace('/<!--.*?-->/s', '', $content);

                foreach ($placeholders as $key => $original) {
                    $content = str_replace($key, $original, $content);
                }

                $response->setContent($content);
            }
        }

        return $response;
    }
}
