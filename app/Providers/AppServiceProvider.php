<?php

namespace App\Providers;

use App\Models\CallLog;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('admin.layout', function ($view) {
            $todayCalls = CallLog::whereDate('created_at', today())->count();
            $todayRevenue = CallLog::whereDate('created_at', today())
                ->where('is_billable', true)
                ->sum('payout');

            $view->with([
                'todayCalls' => $todayCalls,
                'todayRevenue' => $todayRevenue,
            ]);
        });
    }
}
