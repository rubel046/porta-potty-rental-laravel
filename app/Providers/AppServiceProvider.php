<?php

namespace App\Providers;

use App\Http\Controllers\SitemapController;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Models\Faq;
use App\Models\ServicePage;
use App\Models\State;
use App\Models\Testimonial;
use App\Observers\CityObserver;
use App\Observers\FaqObserver;
use App\Observers\TestimonialObserver;
use App\Services\AnthropicService;
use App\Services\GeminiService;
use App\Services\GroqService;
use App\Services\ImageService;
use App\Services\MultiAiService;
use App\Services\OpenAIService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AnthropicService::class);
        $this->app->singleton(OpenAIService::class);
        $this->app->singleton(GeminiService::class);
        $this->app->singleton(GroqService::class);
        $this->app->singleton(ImageService::class);
        $this->app->singleton(MultiAiService::class);
        $this->app->singleton(ContentGeneratorService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $cdnUrl = config('app.cdn_url');
        if (! empty($cdnUrl)) {
            $this->app['url']->formatAssetUrlUsing(function ($path) use ($cdnUrl) {
                return rtrim($cdnUrl, '/').'/'.ltrim($path, '/');
            });
        }

        $this->sharePhoneVariables();
        $this->shareDomainData();
        $this->registerSitemapObservers();
    }

    protected function sharePhoneVariables(): void
    {
        try {
            if (! Schema::hasTable('domains')) {
                return;
            }
            $domain = Domain::current();
            $phoneRaw = $domain?->phone_raw ?? env('DEFAULT_PHONE_RAW', '+18336529344');
            $phoneDisplay = $domain?->phone_display ?? env('DEFAULT_PHONE_DISPLAY', '(833) 652-9344');

            View::share([
                'globalPhoneRaw' => $phoneRaw,
                'globalPhoneDisplay' => $phoneDisplay,
            ]);
        } catch (Throwable $e) {
            // Skip during migrations
        }
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
            // Skip during migrations
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

        City::observe(CityObserver::class);
        Faq::observe(FaqObserver::class);
        Testimonial::observe(TestimonialObserver::class);
    }
}
