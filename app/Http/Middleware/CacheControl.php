<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControl
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        $isHtml = str_contains($contentType, 'text/html');

        if (! $isHtml) {
            return $response;
        }

        if ($request->user() || str_starts_with($request->path(), 'admin')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

            return $response;
        }

        $response->headers->set('Cache-Control', 'public, max-age=3600, must-revalidate');

        if (! $response->headers->has('ETag')) {
            $content = $response->getContent();
            if ($content) {
                $response->headers->set('ETag', '"'.md5($content).'"');
            }
        }

        return $response;
    }
}
