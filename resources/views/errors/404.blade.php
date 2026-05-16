@php
$domain = \App\Models\Domain::current();
$businessName = $domain?->business_name ?? 'Potty Direct';
$primaryService = $domain?->primary_service ?? 'porta potty rental';
$phoneRaw = domain_phone_raw();
$phoneDisplay = domain_phone_display();
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
], JSON_UNESCAPED_SLASHES);
@endphp

@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', '404 - Page Not Found | ' . $businessName)
@section('meta_description', 'Page not found? We can still help you find porta potty rental pricing, services, and locations near you with same-day delivery across the USA.')
@section('canonical', $homeUrl)

@push('schema')
<script type="application/ld+json">{!! $schema404 !!}</script>
@endpush

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg">
        <div class="text-8xl font-bold text-amber-500 mb-2">404</div>
        <h1 class="text-2xl font-bold text-slate-800 mb-3">Page Not Found &mdash; Let Us Help</h1>
        <p class="text-slate-500 mb-6">
            The page you are looking for does not exist. Let us help you find what you need.
        </p>

        @if($phoneRaw)
        <div class="mb-8">
            <a href="tel:{{ $phoneRaw }}"
               data-tracking-label="404-cta"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-4 px-8 rounded-full shadow-lg transition-all hover:scale-[1.02] text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Call {{ $phoneDisplay }}
            </a>
            <p class="text-xs text-slate-400 mt-2">Real person answers in under 30 seconds</p>
        </div>
        @endif

        <h2 class="text-lg font-semibold text-slate-700 mb-4">Quick Links</h2>
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
            <a href="{{ route('home') }}"
               class="bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-3 px-6 rounded-lg transition-all">
                Go Home
            </a>
            <a href="{{ route('locations') }}"
               class="bg-white border-2 border-slate-200 hover:border-emerald-500 text-slate-700 hover:text-emerald-600 font-semibold py-3 px-6 rounded-lg transition-all">
                Browse Locations
            </a>
        </div>

        <div class="text-sm text-slate-500 space-y-2">
            <p>Need {{ $primaryService }}?</p>
            <div class="flex flex-wrap justify-center gap-2 mt-3">
                <a href="{{ route('pricing') }}" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-2 font-medium">Pricing</a>
                <a href="{{ url('/services') }}" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-2 font-medium">Services</a>
                <a href="{{ route('faq') }}" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-2 font-medium">FAQ</a>
                <a href="{{ route('calculator') }}" class="text-emerald-600 hover:text-emerald-700 underline underline-offset-2 font-medium">Calculator</a>
            </div>
        </div>
    </div>
</div>
@endsection
