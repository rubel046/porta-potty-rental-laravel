<?php

namespace App\Observers;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class CityObserver
{
    public function created(City $city): void
    {
        $this->clearCache($city);
    }

    public function updated(City $city): void
    {
        $this->clearCache($city);
    }

    public function deleted(City $city): void
    {
        $this->clearCache($city);
    }

    protected function clearCache(City $city): void
    {
        $domainId = $city->domains->first()?->id ?? 'default';
        Cache::forget("featured_cities_{$domainId}");
        Cache::forget("top_cities_for_schema_{$domainId}");
        Cache::forget('active_states');
        Cache::forget("page_{$city->slug}");
    }
}
