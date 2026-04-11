<?php

namespace App\Providers;

use App\Http\Controllers\SitemapController;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CallLog;
use App\Models\Domain;
use App\Models\ServicePage;
use App\Models\State;
use App\Services\AnthropicService;
use App\Services\GeminiService;
use App\Services\GroqService;
use App\Services\ImageService;
use App\Services\MultiAiService;
use App\Services\OpenAIService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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

        // Share domain data globally for public site components
        $this->shareDomainData();

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

    protected function shareDomainData(): void
    {
        try {
            if (! Schema::hasTable('domains')) {
                return;
            }
            $host = request()->getHost();
            $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

            $domain = Domain::where('domain', $host)->first();

            if (! $domain) {
                $domain = Domain::first();
            }

            view()->share([
                'domainData' => $domain,
                'themeColor' => $domain?->theme_color ?? '#22C55E',
                'logoPath' => $domain?->logo_path,
            ]);
        } catch (Throwable $e) {
            // Skip during migrations or when table doesn't exist
        }

        Blade::directive('domain_view', function ($view) {
            return "(\\App\\Providers\\DomainViewHelper::resolve({$view}))";
        });
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
