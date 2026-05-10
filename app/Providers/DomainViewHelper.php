<?php

namespace App\Providers;

class DomainViewHelper
{
    protected static function getDomainPrefix(): string
    {
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        if (view()->exists("domains.{$prefix}.layout")) {
            return $prefix;
        }

        // Fall back to APP_DOMAIN env when serving a single domain (e.g. artisan serve)
        if (app()->isLocal()) {
            $envDomain = env('APP_DOMAIN');
            if ($envDomain && view()->exists("domains.{$envDomain}.layout")) {
                return $envDomain;
            }
        }

        return 'pottydirect';
    }

    public static function resolve(string $view): string
    {
        $prefix = self::getDomainPrefix();

        $domainView = "domains.{$prefix}.{$view}";

        if (view()->exists($domainView)) {
            return $domainView;
        }

        throw new \RuntimeException("View [{$domainView}] not found for domain [{$prefix}]. Each domain must have its own views.");
    }

    public static function resolveForController(string $page): string
    {
        $prefix = self::getDomainPrefix();

        $domainView = "domains.{$prefix}.{$page}";

        if (view()->exists($domainView)) {
            return $domainView;
        }

        throw new \RuntimeException("View [{$domainView}] not found for domain [{$prefix}]. Each domain must have its own views.");
    }
}
