<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UnitsCalculator extends Component
{
    public function render(): View|Closure|string
    {
        return view('components.units-calculator');
    }
}
