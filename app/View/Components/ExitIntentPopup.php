<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExitIntentPopup extends Component
{
    public function __construct(
        public string $discount = '10',
        public string $title = 'Wait! Don\'t Leave Yet!',
        public string $message = 'Get %DISCOUNT% off your first rental when you call us now!',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.exit-intent-popup');
    }
}
