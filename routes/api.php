<?php

// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Call click tracking (from frontend JS)
Route::post('/track-call-click', function (Request $request) {
    Log::channel('calls')->info('Call click tracked', [
        'phone' => $request->input('phone'),
        'page' => $request->input('page'),
        'source' => $request->input('source'),
        'utm_source' => $request->input('utm_source'),
        'utm_medium' => $request->input('utm_medium'),
        'utm_campaign' => $request->input('utm_campaign'),
        'referrer' => $request->input('referrer'),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'timestamp' => now()->toISOString(),
    ]);

    return response()->json(['status' => 'ok']);
})->name('api.track-call');

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'app' => config('app.name'),
    ]);
})->name('api.health');
