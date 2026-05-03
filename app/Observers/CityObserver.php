<?php

namespace App\Observers;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class CityObserver
{
    public function saved(City $city): void
    {
        $this->clearCache($city);
    }

    public function deleted(City $city): void
    {
        $this->clearCache($city);
    }

    protected function clearCache(City $city): void
    {
        // Forget homepage aggregates across all domains this city belongs to
        foreach ($city->domains as $domain) {
            Cache::forget("home_top_cities_{$domain->id}");
            Cache::forget("home_stats_{$domain->id}");
            Cache::forget("locations_states_{$domain->id}");
        }

        Cache::forget('home_top_cities_default');
        Cache::forget('home_stats_default');
        Cache::forget('locations_states_default');
        Cache::forget('home_active_states_default');

        // Flush per-service-page data caches for every page belonging to this city
        foreach ($city->servicePages as $page) {
            Cache::forget("service_data_{$page->id}");
        }
    }
}
