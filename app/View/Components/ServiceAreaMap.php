<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceAreaMap extends Component
{
    public function __construct(
        public string $height = '400px',
        public array $markers = [],
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.service-area-map');
    }
}
