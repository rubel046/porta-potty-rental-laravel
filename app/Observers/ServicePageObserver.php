<?php

namespace App\Observers;

use App\Http\Controllers\SitemapController;
use App\Models\ServicePage;
use Illuminate\Support\Facades\Cache;

class ServicePageObserver
{
    public function saved(ServicePage $servicePage): void
    {
        $this->clearCache($servicePage);
        SitemapController::invalidateCache();
    }

    public function deleted(ServicePage $servicePage): void
    {
        $this->clearCache($servicePage);
        SitemapController::invalidateCache();
    }

    protected function clearCache(ServicePage $servicePage): void
    {
        $domainId = $servicePage->domain_id ?? 'default';

        Cache::forget("service_data_{$servicePage->id}");
        Cache::forget("slug_404_{$domainId}_{$servicePage->slug}");

        if ($original = $servicePage->getOriginal('slug')) {
            if ($original !== $servicePage->slug) {
                Cache::forget("slug_404_{$domainId}_{$original}");
            }
        }
    }
}
