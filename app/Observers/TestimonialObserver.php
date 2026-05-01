<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    public function created(Testimonial $testimonial): void
    {
        $this->clearCache($testimonial);
    }

    public function updated(Testimonial $testimonial): void
    {
        $this->clearCache($testimonial);
    }

    public function deleted(Testimonial $testimonial): void
    {
        $this->clearCache($testimonial);
    }

    protected function clearCache(Testimonial $testimonial): void
    {
        if ($testimonial->city_id) {
            $city = $testimonial->city;
            if ($city) {
                Cache::forget("page_{$city->slug}");
            }
        }
    }
}
