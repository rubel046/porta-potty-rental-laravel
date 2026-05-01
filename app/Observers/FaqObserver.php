<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    public function created(Faq $faq): void
    {
        $this->clearCache($faq);
    }

    public function updated(Faq $faq): void
    {
        $this->clearCache($faq);
    }

    public function deleted(Faq $faq): void
    {
        $this->clearCache($faq);
    }

    protected function clearCache(Faq $faq): void
    {
        if ($faq->city_id) {
            $city = $faq->city;
            if ($city) {
                Cache::forget("faqs_{$city->id}_{$faq->service_type}");
                Cache::forget("faqs_{$city->id}_general");
                Cache::forget("page_{$city->slug}");
            }
        }
    }
}
