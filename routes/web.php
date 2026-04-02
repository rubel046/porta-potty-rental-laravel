<?php

// routes/web.php

use App\Http\Controllers\Admin\BlogPostController;
// Public Controllers
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
// Admin Controllers
use App\Http\Controllers\Admin\PhoneNumberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServicePageController;
use App\Http\Controllers\BlogController;
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

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

Route::get('/sitemap-cities.xml', [SitemapController::class, 'cities'])
    ->name('sitemap.cities');

Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])
    ->name('sitemap.blog');

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms-of-service', 'pages.terms')->name('terms');

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

    // Auto-generate service pages for a city
    Route::post('/cities/{city}/generate-pages', [CityController::class, 'generatePages'])
        ->name('cities.generate-pages');

    /*
    |----------------------------------------------------------------------
    | Service Pages Management
    |----------------------------------------------------------------------
    */
    Route::resource('service-pages', ServicePageController::class)
        ->except(['create', 'store', 'destroy']);
    // Note: create/store/destroy excluded intentionally - pages are auto-generated from cities

    /*
    |----------------------------------------------------------------------
    | Blog Posts Management
    |----------------------------------------------------------------------
    */
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
    Route::resource('phone-numbers', PhoneNumberController::class)
        ->only(['index', 'create', 'destroy']);

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

Route::get('/{slug}', [PageController::class, 'cityPage'])
    ->name('service.page')
    ->where('slug', '[a-z0-9][a-z0-9\-]*');
