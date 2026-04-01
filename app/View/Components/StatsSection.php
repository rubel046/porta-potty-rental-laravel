<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatsSection extends Component
{
    public function __construct(
        public array $stats = [],
    ) {}

    public function render(): View|Closure|string
    {
        $defaultStats = [
            [
                'value' => '15+',
                'label' => 'Years Experience',
                'icon' => 'calendar',
            ],
            [
                'value' => '50,000+',
                'label' => 'Units Delivered',
                'icon' => 'truck',
            ],
            [
                'value' => '500+',
                'label' => 'Happy Customers',
                'icon' => 'heart',
            ],
            [
                'value' => '24/7',
                'label' => 'Customer Support',
                'icon' => 'clock',
            ],
        ];

        $this->stats = ! empty($this->stats) ? $this->stats : $defaultStats;

        return view('components.stats-section');
    }
}
