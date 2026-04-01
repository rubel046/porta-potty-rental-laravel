<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TrustBadges extends Component
{
    public function __construct(
        public bool $showRating = true,
        public bool $showLicensed = true,
        public bool $showDelivery = true,
        public bool $showPricing = true,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.trust-badges');
    }
}
