<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->sanitizeSessionData($request);

        return $next($request);
    }

    protected function sanitizeSessionData(Request $request): void
    {
        if (! $request->hasSession()) {
            return;
        }

        $session = $request->session();
        $flashKeys = $session->get('_flash', []);
        $keysToRemove = [];

        foreach ($flashKeys as $key => $data) {
            $value = $session->get($key);

            if ($this->containsEloquentObject($value)) {
                $session->forget($key);
                $keysToRemove[] = $key;
            }
        }

        foreach ($keysToRemove as $key) {
            unset($flashKeys[$key]);
        }

        if (! empty($keysToRemove)) {
            $session->put('_flash', $flashKeys);
        }
    }

    protected function containsEloquentObject(mixed $value): bool
    {
        if ($value instanceof Collection) {
            return true;
        }

        if ($value instanceof Model) {
            return true;
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->containsEloquentObject($item)) {
                    return true;
                }
            }
        }

        return false;
    }
}
