<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    public function saved(Faq $faq): void
    {
        $this->clearCache($faq);
    }

    public function deleted(Faq $faq): void
    {
        $this->clearCache($faq);
    }

    protected function clearCache(Faq $faq): void
    {
        if (! $faq->city_id) {
            return;
        }

        $city = $faq->city;
        if (! $city) {
            return;
        }

        // Flush all service-page data bundles for this city (they include schema referencing FAQs via view)
        foreach ($city->servicePages as $page) {
            Cache::forget("service_data_{$page->id}");
        }
    }
}
