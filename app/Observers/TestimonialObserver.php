<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    public function saved(Testimonial $testimonial): void
    {
        $this->clearCache($testimonial);
    }

    public function deleted(Testimonial $testimonial): void
    {
        $this->clearCache($testimonial);
    }

    protected function clearCache(Testimonial $testimonial): void
    {
        // Featured testimonials feed the homepage pool — flush per-domain keys
        if ($testimonial->is_featured) {
            Cache::forget('home_testimonial_pool_default');
            // Domain-specific pools are keyed by domain id; flush them all.
            // Testimonials don't have direct domain FK, so flush via city->domains
        }

        if (! $testimonial->city_id) {
            return;
        }

        $city = $testimonial->city;
        if (! $city) {
            return;
        }

        foreach ($city->domains as $domain) {
            Cache::forget("home_testimonial_pool_{$domain->id}");
        }

        foreach ($city->servicePages as $page) {
            Cache::forget("service_data_{$page->id}");
        }
    }
}
