<?php

namespace App\Providers;

use App\Http\Controllers\SitemapController;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CallLog;
use App\Models\ServicePage;
use App\Models\State;
use App\Services\AnthropicService;
use App\Services\GeminiService;
use App\Services\GroqService;
use App\Services\ImageService;
use App\Services\MultiAiService;
use App\Services\OpenAIService;
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
        $this->app->singleton(AnthropicService::class);
        $this->app->singleton(OpenAIService::class);
        $this->app->singleton(GeminiService::class);
        $this->app->singleton(GroqService::class);
        $this->app->singleton(ImageService::class);
        $this->app->singleton(MultiAiService::class);
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

        $this->registerSitemapObservers();
    }

    protected function registerSitemapObservers(): void
    {
        ServicePage::observe(function () {
            SitemapController::invalidateCache();
        });

        BlogPost::observe(function () {
            SitemapController::invalidateCache();
        });

        BlogCategory::observe(function () {
            SitemapController::invalidateCache();
        });

        State::observe(function () {
            SitemapController::invalidateCache();
        });
    }
}
