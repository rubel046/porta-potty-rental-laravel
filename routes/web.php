<?php

// routes/web.php

use App\Http\Controllers\Admin\AiApiKeyController;
// Public Controllers
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\GlobalCityController;
// Admin Controllers
use App\Http\Controllers\Admin\GlobalStateController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LogViewerController;
use App\Http\Controllers\Admin\PhoneNumberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServicePageController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SignalWireWebhookController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — SEO Pages (এগুলো Google ইনডেক্স করবে)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PageController::class, 'home'])
    ->name('home');

// All Locations Page
Route::get('/locations', [PageController::class, 'locations'])
    ->name('locations');

// Services Page
Route::get('/services', [PageController::class, 'services'])
    ->name('services');

// Pricing Page
Route::get('/pricing', [PageController::class, 'pricing'])
    ->name('pricing');

// State Page (e.g., /porta-potty-rental-texas)
Route::get('/porta-potty-rental-{stateSlug}', [PageController::class, 'statePage'])
    ->name('state.page')
    ->where('stateSlug', 'alabama|alaska|arizona|arkansas|california|colorado|connecticut|delaware|florida|georgia|hawaii|idaho|illinois|indiana|iowa|kansas|kentucky|louisiana|maine|maryland|massachusetts|michigan|minnesota|mississippi|montana|nebraska|nevada|new-hampshire|new-jersey|new-mexico|new-york|north-carolina|north-dakota|ohio|oklahoma|oregon|pennsylvania|rhode-island|south-carolina|south-dakota|tennessee|texas|utah|vermont|virginia|washington|west-virginia|wisconsin|wyoming');

// Blog (must come before catch-all)
Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->name('blog.show')
    ->where('slug', '[a-z0-9\-]+');

// Sitemap Index (references sub-sitemaps)
Route::get('/sitemap.xml', [SitemapController::class, 'indexSitemaps'])
    ->name('sitemap');

// Full sitemap (all URLs in one file)
Route::get('/sitemap-full.xml', [SitemapController::class, 'index'])
    ->name('sitemap.full');

// Sub-sitemaps
Route::get('/sitemap-cities.xml', [SitemapController::class, 'cities'])
    ->name('sitemap.cities');

Route::get('/sitemap-states.xml', [SitemapController::class, 'states'])
    ->name('sitemap.states');

Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])
    ->name('sitemap.blog');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');

// Lead Form Submission (public)
Route::post('/lead', [LeadController::class, 'store'])
    ->name('lead.store');

/*
|--------------------------------------------------------------------------
| SignalWire Webhooks (CSRF disabled in bootstrap/app.php)
|--------------------------------------------------------------------------
|
| এই routes গুলোতে SignalWire সার্ভার থেকে POST request আসবে।
| CSRF token থাকবে না তাই bootstrap/app.php তে exempt করা হয়েছে।
|
*/

Route::prefix('webhook/signalwire')->group(function () {

    // কল প্রথম আসলে এখানে হিট হবে
    Route::post('/incoming', [SignalWireWebhookController::class, 'incoming'])
        ->name('sw.incoming');

    // IVR তে কলার কিছু প্রেস করলে এখানে আসবে
    Route::post('/gather', [SignalWireWebhookController::class, 'gather'])
        ->name('sw.gather');

    // বায়ার কল পিক করার আগে whisper শুনবে
    Route::post('/whisper', [SignalWireWebhookController::class, 'whisper'])
        ->name('sw.whisper');

    // কল শেষ হলে status update আসবে
    Route::post('/status', [SignalWireWebhookController::class, 'status'])
        ->name('sw.status');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth Protected)
|--------------------------------------------------------------------------
|
| এই routes গুলো শুধু logged-in user অ্যাক্সেস করতে পারবে।
| Laravel Breeze auth ব্যবহার করা হয়েছে।
|
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Domains Management
    |----------------------------------------------------------------------
    */
    Route::resource('domains', DomainController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/domains/{domain}/switch', [DomainController::class, 'switch'])
        ->name('domains.switch');
    Route::post('/domains/{domain}/sync', [DomainController::class, 'sync'])
        ->name('domains.sync');

    /*
    |----------------------------------------------------------------------
    | Global States Management (Master Data)
    |----------------------------------------------------------------------
    */
    Route::resource('global/states', GlobalStateController::class)->names('global.states');
    Route::post('/global/states/{state}/generate-content', [GlobalStateController::class, 'generateContent'])
        ->name('global.states.generate-content');
    Route::get('/global/states/{state}/generation-progress', [GlobalStateController::class, 'generationProgress'])
        ->name('global.states.generation-progress');

    /*
    |----------------------------------------------------------------------
    | Global Cities Management (Master Data)
    |----------------------------------------------------------------------
    */
    Route::resource('global/cities', GlobalCityController::class)->names('global.cities');
    Route::post('/global/cities/{city}/generate-content', [GlobalCityController::class, 'generatePages'])
        ->name('global.cities.generate-content');
    Route::get('/global/cities/{city}/generation-progress', [GlobalCityController::class, 'generationProgress'])
        ->name('global.cities.generation-progress');
    Route::delete('/global/cities/{city}/pages', [GlobalCityController::class, 'deletePages'])
        ->name('global.cities.delete-pages');

    /*
    |----------------------------------------------------------------------
    | Call Logs
    |----------------------------------------------------------------------
    */
    Route::get('/calls', [DashboardController::class, 'calls'])
        ->name('calls.index');

    /*
    |----------------------------------------------------------------------
    | Cities Management
    |----------------------------------------------------------------------
    */
    Route::resource('cities', CityController::class);

    // Toggle city status for current domain
    Route::post('/cities/{city}/toggle-status', [CityController::class, 'toggleStatus'])
        ->name('cities.toggle-status');

    // Auto-generate service pages for a city
    Route::post('/cities/{city}/generate-pages', [CityController::class, 'generatePages'])
        ->name('cities.generate-pages');

    // Check content generation progress
    Route::get('/cities/{city}/generation-progress', [CityController::class, 'generationProgress'])
        ->name('cities.generation-progress');

    // Delete all service pages, FAQs, and testimonials for a city
    Route::delete('/cities/{city}/delete-pages', [CityController::class, 'deletePages'])
        ->name('cities.delete-pages');

    // Import JSON content for a city
    Route::post('/cities/{city}/import-json', [CityController::class, 'importJson'])
        ->name('cities.import-json');

    // Get sample JSON format for a city
    Route::get('/cities/{city}/sample-json', [CityController::class, 'getSampleJson'])
        ->name('cities.sample-json');

    /*
    |----------------------------------------------------------------------
    | States Management
    |----------------------------------------------------------------------
    */
    Route::resource('states', StateController::class)
        ->only(['index', 'edit', 'update']);

    // Toggle state status for current domain
    Route::post('/states/{state}/toggle-status', [StateController::class, 'toggleStatus'])
        ->name('states.toggle-status');

    // Generate content for a state
    Route::post('/states/{state}/generate-content', [StateController::class, 'generateContent'])
        ->name('states.generate-content');

    // Check content generation progress
    Route::get('/states/{state}/generation-progress', [StateController::class, 'generationProgress'])
        ->name('states.generation-progress');

    /*
    |----------------------------------------------------------------------
    | Service Pages Management
    |----------------------------------------------------------------------
    */
    // Bulk delete service pages (must be before resource routes)
    Route::delete('/service-pages/bulk-destroy', [ServicePageController::class, 'bulkDestroy'])
        ->name('service-pages.bulk-destroy');

    // Quick view API
    Route::get('/service-pages/{servicePage}/quick-view', [ServicePageController::class, 'quickView'])
        ->name('service-pages.quick-view');

    Route::resource('service-pages', ServicePageController::class)
        ->except(['create', 'store']);
    // Note: create/store excluded intentionally - pages are auto-generated from cities

    /*
    |----------------------------------------------------------------------
    | Blog Categories Management
    |----------------------------------------------------------------------
    */
    Route::resource('blog-categories', BlogCategoryController::class);

    /*
    |----------------------------------------------------------------------
    | Blog Posts Management
    |----------------------------------------------------------------------
    */
    Route::get('/blog-posts/generate', [BlogPostController::class, 'generateForm'])
        ->name('blog-posts.generate-form');
    Route::post('/blog-posts/generate', [BlogPostController::class, 'generate'])
        ->name('blog-posts.generate');
    Route::resource('blog-posts', BlogPostController::class);

    /*
    |----------------------------------------------------------------------
    | Buyers Management
    |----------------------------------------------------------------------
    */
    Route::resource('buyers', BuyerController::class);

    /*
    |----------------------------------------------------------------------
    | Phone Numbers Management
    |----------------------------------------------------------------------
    */
    Route::resource('phone-numbers', PhoneNumberController::class);

    // SignalWire থেকে নম্বর কেনা
    Route::post('/phone-numbers/purchase', [PhoneNumberController::class, 'purchase'])
        ->name('phone-numbers.purchase');

    /*
    |----------------------------------------------------------------------
    | Invoices Management
    |----------------------------------------------------------------------
    */
    Route::resource('invoices', InvoiceController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Invoice actions
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])
        ->name('invoices.send');

    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])
        ->name('invoices.mark-paid');

    /*
    |----------------------------------------------------------------------
    | Reports
    |----------------------------------------------------------------------
    */
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports');

    /*
    |----------------------------------------------------------------------
    | AI API Keys
    |----------------------------------------------------------------------
    */
    Route::resource('api-keys', AiApiKeyController::class);
    Route::post('/api-keys/{apiKey}/toggle', [AiApiKeyController::class, 'toggle'])
        ->name('api-keys.toggle');
    Route::post('/api-keys/{apiKey}/reset', [AiApiKeyController::class, 'reset'])
        ->name('api-keys.reset');
    Route::post('/api-keys/reset-all', [AiApiKeyController::class, 'resetAll'])
        ->name('api-keys.reset-all');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
|
| Laravel Breeze install করলে এটি automatically যোগ হয়।
| যদি না থাকে, নিচের লাইনটি uncomment করুন।
|
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| City/Service Page Catch-All Route (এটি সবার শেষে থাকতে হবে!)
|--------------------------------------------------------------------------
|
| এই route টি সব ধরনের service page slug ক্যাচ করবে।
| যেমন:
|   /porta-potty-rental-houston-tx
|   /construction-porta-potty-rental-houston-tx
|   /wedding-porta-potty-rental-houston-tx
|   /event-porta-potty-rental-houston-tx
|
| ⚠️ গুরুত্বপূর্ণ: এই route অবশ্যই সবার শেষে থাকতে হবে!
| কারণ এটি যেকোনো slug ম্যাচ করবে। উপরের নির্দিষ্ট routes
| আগে চেক হবে, ম্যাচ না হলে এখানে আসবে।
|
*/

/*
|----------------------------------------------------------------------
| System Logs (must be before catch-all)
|----------------------------------------------------------------------
*/
Route::prefix('admin/logs')->name('admin.logs.')->middleware(['auth'])->group(function () {
    Route::get('/', [LogViewerController::class, 'index'])->name('index');
    Route::get('/show', [LogViewerController::class, 'show'])->name('show');
    Route::post('/clear', [LogViewerController::class, 'clear'])->name('clear');
    Route::post('/download', [LogViewerController::class, 'download'])->name('download');
});

Route::get('/{slug}', [PageController::class, 'cityPage'])
    ->name('service.page')
    ->where('slug', '[a-z0-9][a-z0-9\-]*');
