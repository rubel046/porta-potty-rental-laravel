@php
$domain = \App\Models\Domain::current();
$businessName = $domain?->business_name ?? 'Potty Direct';
$primaryService = $domain?->primary_service ?? 'porta potty rental';
$currentUrl = url()->current();
$homeUrl = url('/');
$schema404 = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => '404 - Page Not Found',
    'description' => 'Page not found. Browse porta potty rental pricing, services, and locations.',
    'url' => $currentUrl,
    'mainEntity' => [
        '@type' => 'SiteNavigationElement',
        'name' => $primaryService,
        'url' => $homeUrl,
    ],
], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | {{ $businessName }}</title>
    <meta name="description" content="Page not found? We can still help you find porta potty rental pricing, services, and locations near you with same-day delivery across the USA.">
    <meta name="robots" content="noindex,follow">
    <link rel="canonical" href="{{ $homeUrl }}">
    <script type="application/ld+json">{!! $schema404 !!}</script>
    <meta property="og:title" content="404 - Page Not Found | {{ $businessName }}">
    <meta property="og:description" content="Page not found? Browse porta potty rental pricing, services, and locations.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:site_name" content="{{ $businessName }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center p-4">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-bold text-amber-500 mb-2">404</div>
        <h1 class="text-2xl font-bold text-white mb-3">Page Not Found &mdash; Let Us Help</h1>
        <p class="text-slate-400 mb-6">
            The page you are looking for does not exist. Let us help you find what you need.
        </p>
        <h2 class="text-lg font-semibold text-slate-300 mb-4">Quick Links</h2>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-3 px-6 rounded-lg transition-all">
                Go Home
            </a>
            <a href="/locations" class="bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-6 rounded-lg transition-all border border-white/20">
                Browse Locations
            </a>
        </div>
        <div class="mt-8 text-sm text-slate-500 space-y-2">
            <p>Need {{ $primaryService }}?</p>
            <div class="flex flex-wrap justify-center gap-2 mt-3">
                <a href="/pricing" class="text-amber-400 hover:text-amber-300 underline underline-offset-2">Pricing</a>
                <a href="/services" class="text-amber-400 hover:text-amber-300 underline underline-offset-2">Services</a>
                <a href="/faq" class="text-amber-400 hover:text-amber-300 underline underline-offset-2">FAQ</a>
                <a href="/units-calculator" class="text-amber-400 hover:text-amber-300 underline underline-offset-2">Calculator</a>
            </div>
        </div>
    </div>
</body>
</html>
