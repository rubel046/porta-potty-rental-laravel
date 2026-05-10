@php
$domain = \App\Models\Domain::current();
$businessName = $domain?->business_name ?? 'Plumbing Pro';
$primaryService = $domain?->primary_service ?? 'plumbing services';
$phoneRaw = $domain?->cta_phone ?? phone_raw();
$phoneDisplay = $domain ? format_phone_display($domain->cta_phone) : phone_display();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $businessName.' | '.$primaryService)</title>
    <meta name="description" content="@yield('meta_description', 'Need '.$primaryService.'? '.$businessName.' offers professional '.$primaryService.' services nationwide. Call '.$phoneDisplay.'!')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @section('og_image')
    <meta property="og:image" content="{{ url('/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $businessName }} - {{ ucfirst($primaryService) }}">
    <meta name="twitter:image" content="{{ url('/og-image.jpg') }}">
    <meta name="twitter:image:alt" content="{{ $businessName }} - {{ ucfirst($primaryService) }}">
    @show

    @stack('schema')

    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:site_name" content="{{ $businessName }}">
    <meta property="og:locale" content="en_US">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">

    <link rel="alternate" hreflang="en" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="en-US" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="x-default" href="@yield('canonical', url()->current())">

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="{{ $businessName }}">
    <meta name="geo.region" content="US">
    <meta name="geo.placename" content="United States">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="theme-color" content="#273bd3">

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔧</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    {{-- Google Analytics 4 --}}
    @if(config('services.ga4.measurement_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.ga4.measurement_id') }}');
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<div class="min-h-screen flex flex-col">

    {{-- Announcement Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2 px-3 sm:px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
            <span class="font-medium text-center sm:text-left">24/7 {{ ucfirst($primaryService) }} Service Available</span>
            <a href="tel:{{ $phoneRaw }}" class="ml-0 sm:ml-2 bg-white hover:bg-blue-50 text-blue-600 hover:text-blue-700 px-3 py-1 rounded-full font-bold transition text-sm shadow-sm inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                {{ $phoneDisplay }}
            </a>
        </div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <a href="/" class="flex items-center gap-2 sm:gap-3 group flex-shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($businessName, 0, 1)) }}{{ strtoupper(substr(explode(' ', $businessName)[1] ?? str_replace(['Pro', 'Inc', 'LLC'], '', $businessName), 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-sm font-bold text-slate-800 leading-tight">{{ $businessName }}</div>
                        <div class="text-xs text-blue-600 font-medium leading-tight">{{ ucfirst($primaryService) }}</div>
                    </div>
                </a>
                <div>
                    <a href="tel:{{ $phoneRaw }}" class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-bold py-2 px-4 rounded-full shadow-lg shadow-blue-500/25 transition-all hover:scale-105">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="hidden lg:inline">{{ $phoneDisplay }}</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300 py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-xs sm:text-sm text-slate-500 text-center sm:text-left">
                    &copy; {{ date('Y') }} {{ $businessName }}. All rights reserved.
                </p>
                <div class="flex items-center gap-3 sm:gap-6 text-xs sm:text-sm text-slate-500">
                    <a href="/" class="hover:text-white transition">Home</a>
                    <a href="/locations" class="hover:text-white transition">Locations</a>
                    <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click_to_call', { event_category: 'engagement', event_label: this.href, page_path: window.location.pathname });
            }
        });
    });
</script>

@include('components.phone-tracker')
@stack('scripts')
</body>
</html>