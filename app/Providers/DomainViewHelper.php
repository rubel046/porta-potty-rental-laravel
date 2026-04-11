<?php

namespace App\Providers;

class DomainViewHelper
{
    protected static function getDomainPrefix(): string
    {
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        // In local/development mode, check APP_DOMAIN env
        if (app()->isLocal() || app()->environment('local', 'development')) {
            $envDomain = env('APP_DOMAIN');
            if ($envDomain) {
                return $envDomain;
            }
        }

        return $prefix;
    }

    public static function resolve(string $view): string
    {
        $prefix = self::getDomainPrefix();

        $domainView = "domains.{$prefix}.{$view}";

        if (view()->exists($domainView)) {
            return $domainView;
        }

        return "domains.pottydirect.{$view}";
    }

    public static function resolveForController(string $page): string
    {
        $prefix = self::getDomainPrefix();

        $domainView = "domains.{$prefix}.{$page}";

        if (view()->exists($domainView)) {
            return $domainView;
        }

        return "domains.pottydirect.{$page}";
    }
}
