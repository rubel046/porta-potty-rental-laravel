<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $domain = \App\Models\Domain::current();
    @endphp
    @php
        $phoneRaw = $globalPhoneRaw ?? domain_phone_raw();
        $phoneDisplay = $globalPhoneDisplay ?? domain_phone_display();
        if ($overrideRaw = trim(View::yieldContent('phone_raw'))) {
            $phoneRaw = $overrideRaw;
        }
        if ($overrideDisplay = trim(View::yieldContent('phone_display'))) {
            $phoneDisplay = $overrideDisplay;
        }
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Porta Potty Rental | Same Day Delivery | Construction & Event Toilets')</title>
    <meta name="description"
          content="@yield('meta_description', 'Need '.($domain?->primary_service ?? 'porta potty rental').'? '.($domain?->business_name ?? 'Potty Direct').' offers professional service. Get your personalized quote today. Call '.$phoneDisplay.'!')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Custom Open Graph Image (can be overridden per page) --}}
    @section('og_image')
        <meta property="og:image" content="{{ url('/og-image.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $domain?->business_name ?? 'Potty Direct' }} - {{ $domain?->primary_service ?? 'Portable Restroom Rental' }}">
        <meta name="twitter:image" content="{{ url('/og-image.jpg') }}">
        <meta name="twitter:image:alt" content="{{ $domain?->business_name ?? 'Potty Direct' }} - {{ $domain?->primary_service ?? 'Portable Restroom Rental' }}">
    @show

    {{-- OpenGraph Location Tags for Local SEO --}}
    <meta property="og:latitude" content="{{ $latitude ?? 32.7767 }}">
    <meta property="og:longitude" content="{{ $longitude ?? -96.7970 }}">
    <meta property="og:locality" content="{{ $cityAddress ?? ($topCities[0]['name'] ?? 'Dallas') }}">
    <meta property="og:region" content="{{ $stateCodeLocal ?? ($topCities[0]['state']['code'] ?? 'TX') }}">
    <meta property="og:postal-code" content="{{ $postalCode ?? ($topCities[0]['zip_code'] ?? '75201') }}">
    <meta property="og:country-name" content="USA">

    {{-- Schema.org JSON-LD --}}
    {{-- Site-wide Organization + WebSite schema (per-page schemas push to the stack below) --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => ['Organization', 'HomeAndConstructionBusiness'],
                    '@id' => url('/') . '#organization',
                    'name' => $domain?->business_name ?? 'Potty Direct',
                    'url' => url('/'),
                    'logo' => url('/favicon.svg'),
                    'telephone' => $phoneRaw,
                    'sameAs' => array_values(array_filter([
                        $domain?->google_business_url ?? null,
                        'https://youtube.com/@pottydirect',
                    ])),
                    'contactPoint' => [[
                        '@type' => 'ContactPoint',
                        'telephone' => $phoneRaw,
                        'contactType' => 'customer service',
                        'areaServed' => 'US',
                        'availableLanguage' => ['English'],
                        'hoursAvailable' => [[
                            '@type' => 'OpeningHoursSpecification',
                            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                            'opens' => config('contact.hours_open', '07:00'),
                            'closes' => config('contact.hours_close', '20:00'),
                        ]],
                    ]],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/'),
                    'name' => $domain?->business_name ?? 'Potty Direct',
                    'publisher' => ['@id' => url('/') . '#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => url('/locations') . '?q={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('schema')

    {{-- Open Graph / Social --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:site_name" content="{{ $domain?->business_name ?? 'Potty Direct' }}">
    <meta property="og:locale" content="en_US">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    @if($domain?->twitter_handle ?? null)
        <meta name="twitter:site" content="@{{ ltrim($domain->twitter_handle, '@') }}">
    @endif

    {{-- Hreflang (single-locale site — only en-US and x-default) --}}
    <link rel="alternate" hreflang="en-US" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="x-default" href="@yield('canonical', url()->current())">

    {{-- Pagination SEO (for paginated pages) --}}
    @yield('pagination_headers')

    {{-- Additional SEO --}}
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="{{ $domain?->business_name ?? 'Potty Direct' }}">
    <meta name="geo.region" content="US">
    <meta name="geo.placename" content="United States">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="theme-color" content="#10b981">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ url('/favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ url('/favicon.svg') }}">

    {{-- Preconnect for performance --}}
    {{-- Note: Plus Jakarta Sans was never actually loaded — site renders in the system-ui fallback.
         To self-host, drop woff2 files at /public/fonts/jakarta-400.woff2 / 600.woff2 / 700.woff2
         and add @font-face + <link rel="preload"> here. --}}
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    {{-- Google Analytics 4 --}}
    @if(config('services.ga4.measurement_id'))
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '{{ config('services.ga4.measurement_id') }}');
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased"
      x-data="{ mobileMenu: false, services: false }"
      x-effect="document.body.style.overflow = mobileMenu ? 'hidden' : ''"
      @keydown.escape.window="mobileMenu = false; services = false">

{{-- Skip link for keyboard users — visually hidden until focused --}}
<a href="#main"
   class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[10000] focus:px-4 focus:py-2 focus:bg-emerald-600 focus:text-white focus:rounded-lg focus:shadow-lg">
    Skip to main content
</a>

{{-- Announcement Banner - Time-of-day aware --}}
@php
    $isOpenNow = business_is_open_now();
    $opensAtLabel = business_opens_at_label();
@endphp
<div id="announcement-banner" class="bg-gradient-to-r from-amber-500 to-amber-600 text-white py-2 px-3 sm:px-4">
    <div
        class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
        @if($isOpenNow)
            <x-icon name="bolt" class="hidden sm:inline-block w-4 h-4 text-white/90"/>
            <span class="font-semibold text-center sm:text-left">Same-Day Delivery: Order by 2PM</span>
            <span class="hidden md:inline text-amber-100">• Limited availability</span>
        @else
            <x-icon name="moon" class="hidden sm:inline-block w-4 h-4 text-white/90"/>
            <span class="font-semibold text-center sm:text-left">We open at {{ $opensAtLabel }} — leave a message or call for emergency service</span>
        @endif
        <a href="tel:{{ $phoneRaw }}" data-tracking-label="banner"
           class="hidden sm:inline-flex sm:ml-2 bg-white hover:bg-amber-50 text-amber-600 hover:text-amber-700 px-3 sm:px-3 py-1.5 sm:py-1 rounded-full font-bold transition text-sm sm:text-sm whitespace-nowrap shadow-sm items-center gap-1.5 min-h-[44px]">
            <x-icon name="phone" class="w-4 h-4"/>
            {{ $phoneDisplay }}
        </a>
    </div>
</div>

{{-- Header --}}
<header id="header"
        class="sticky top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16 lg:h-18">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group flex-shrink-0 my-1">
                <div
                    class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
                    <span class="text-white font-extrabold text-base sm:text-lg leading-none tracking-tight">
                        {{ strtoupper(substr($domain?->business_name ?? 'PD', 0, 1)) }}{{ strtoupper(substr(explode(' ', $domain?->business_name ?? 'Potty Direct')[1] ?? 'D', 0, 1)) }}
                    </span>
                </div>
                <div class="hidden sm:block">
                    <div
                        class="text-sm font-bold text-slate-800 leading-tight">{{ $domain?->business_name ?? 'Potty Direct' }}</div>
                    <div
                        class="text-xs text-emerald-600 font-medium leading-tight">{{ $domain?->primary_service ?? 'Portable Restrooms' }}</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                {{-- Services Dropdown --}}
                <div class="relative" @click.away="services = false">
                    <button type="button"
                            @click="services = !services"
                            :aria-expanded="services ? 'true' : 'false'"
                            aria-haspopup="true"
                            class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all flex items-center gap-1">
                        Services
                        <svg aria-hidden="true" class="w-4 h-4 transition-transform"
                             :class="services ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="services"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="absolute top-full left-0 pt-2 z-50" style="display: none;">
                        <div
                            class="bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-100 py-2 w-56">
                            <a href="{{ route('services') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-emerald-600 font-medium hover:bg-emerald-50 transition border-b border-slate-100 min-h-[44px]">
                                <x-icon name="shield-check" class="w-5 h-5 flex-shrink-0"/>
                                <div>
                                    <div class="font-medium">View All Services</div>
                                    <div class="text-xs text-slate-400">Complete service listing</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#standard"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="building" class="w-5 h-5 flex-shrink-0 text-emerald-500"/>
                                <div>
                                    <div class="font-medium">Standard Units</div>
                                    <div class="text-xs text-slate-400">Basic portable toilets</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#deluxe"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="water-drop" class="w-5 h-5 flex-shrink-0 text-emerald-500"/>
                                <div>
                                    <div class="font-medium">Deluxe Flushable</div>
                                    <div class="text-xs text-slate-400">With hand wash station</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#ada"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="accessibility" class="w-5 h-5 flex-shrink-0 text-emerald-500"/>
                                <div>
                                    <div class="font-medium">ADA Accessible</div>
                                    <div class="text-xs text-slate-400">Wheelchair friendly</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#luxury"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="sparkles" class="w-5 h-5 flex-shrink-0 text-amber-500"/>
                                <div>
                                    <div class="font-medium">Luxury Trailers</div>
                                    <div class="text-xs text-slate-400">Premium restroom trailers</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#shower"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="shower" class="w-5 h-5 flex-shrink-0 text-emerald-500"/>
                                <div>
                                    <div class="font-medium">Portable Showers</div>
                                    <div class="text-xs text-slate-400">Hot & cold water units</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#construction"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition min-h-[44px]">
                                <x-icon name="building" class="w-5 h-5 flex-shrink-0 text-emerald-500"/>
                                <div>
                                    <div class="font-medium">Construction Packages</div>
                                    <div class="text-xs text-slate-400">Bulk pricing for job sites</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Locations --}}
                <a href="{{ route('locations') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Locations
                </a>

                {{-- Pricing --}}
                <a href="{{ route('pricing') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Pricing
                </a>

                {{-- Blog --}}
                <a href="{{ route('blog.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Blog
                </a>

                {{-- About --}}
                <a href="{{ route('about') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    About
                </a>
            </nav>

            {{-- Desktop Search Bar --}}
            <div class="hidden md:flex items-center flex-1 max-w-xs mx-4 lg:mx-6">
                <div class="relative w-full">
                    <input type="text" id="header-search" data-locations-url="{{ route('locations') }}"
                           placeholder="Search city or zip..."
                           class="w-full bg-slate-100 hover:bg-slate-50 border border-slate-200 focus:border-emerald-300 focus:ring-2 focus:ring-emerald-100 rounded-full py-2 pl-10 pr-4 text-sm transition outline-none">
                    <svg aria-hidden="true" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="tel:{{ $phoneRaw }}" data-tracking-label="header"
                   class="flex items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white text-xs sm:text-sm font-bold py-2 px-2.5 sm:px-4 rounded-full shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all hover:scale-105 ring-2 ring-amber-400/30 min-h-[44px]"
                   aria-label="Call {{ $phoneDisplay }}">
                    <svg aria-hidden="true" class="w-3.5 h-3.5 sm:w-4 sm:h-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    {{-- Always show the actual number: crucial for local-service CTR --}}
                    <span class="whitespace-nowrap">{{ $phoneDisplay }}</span>
                </a>

                <button type="button"
                        @click="mobileMenu = !mobileMenu"
                        :aria-expanded="mobileMenu ? 'true' : 'false'"
                        class="lg:hidden p-2 h-auto min-h-[44px] min-w-[44px] rounded-lg hover:bg-slate-100 transition flex items-center justify-center"
                        aria-label="Menu">
                    <svg x-show="!mobileMenu" class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenu" x-cloak class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Side Sheet --}}
    @php
        $isRoute = fn(string $name) => request()->routeIs($name);
        $isBlog = request()->is('blog*');
        $activeClasses = 'bg-emerald-50 text-emerald-700 border-emerald-500';
        $inactiveClasses = 'text-slate-700 hover:bg-slate-50 border-transparent';
    @endphp
    <div class="lg:hidden fixed inset-0 z-[9999]"
         x-cloak
         :class="mobileMenu ? 'block' : 'hidden'">
        {{-- Backdrop with fade --}}
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300"
             :class="mobileMenu ? 'opacity-100' : 'opacity-0'"
             @click="mobileMenu = false"></div>

        {{-- Sidebar with slide animation --}}
        <aside
            class="fixed inset-y-0 right-0 w-[85vw] max-w-sm bg-white shadow-2xl flex flex-col transition-transform duration-300 ease-out will-change-transform"
            :class="mobileMenu ? 'translate-x-0' : 'translate-x-full'">
            {{-- Header bar: brand + close --}}
            <div class="flex items-center justify-between px-4 py-2 border-b border-slate-200 flex-shrink-0">
                <a href="{{ route('home') }}" @click="mobileMenu = false" class="flex items-center gap-2.5">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                          <span class="text-white font-extrabold text-base leading-none tracking-tight">
                              {{ strtoupper(substr($domain?->business_name ?? 'PD', 0, 1)) }}{{ strtoupper(substr(explode(' ', $domain?->business_name ?? 'Potty Direct')[1] ?? 'D', 0, 1)) }}
                          </span>
                    </div>
                    <div>
                        <div
                            class="text-sm font-bold text-slate-800 leading-tight">{{ $domain?->business_name ?? 'Potty Direct' }}</div>
                        <div
                            class="text-[10px] text-emerald-600 font-medium leading-tight">{{ $domain?->primary_service ?? 'Portable Restrooms' }}</div>
                    </div>
                </a>
                <button type="button"
                        @click="mobileMenu = false"
                        class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-700 transition"
                        aria-label="Close menu">
                    <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Scrollable nav --}}
            <nav class="flex-1 overflow-y-auto py-2" aria-label="Primary navigation"
                 style="display: table; background: inherit">
                {{-- Home --}}
                <a href="{{ route('home') }}"
                   @click="mobileMenu = false"
                   @class([
                       'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                       $activeClasses => $isRoute('home'),
                       $inactiveClasses => ! $isRoute('home'),
                   ])
                   @if($isRoute('home')) aria-current="page" @endif>
                    <x-icon name="home" class="w-5 h-5"/>
                    <span>Home</span>
                </a>

                {{-- Services — expandable accordion (7 sub-types) --}}
                <div x-data="{ open: @json($isRoute('services')) }" class="mx-2 my-0.5">
                    <button type="button"
                            @click="open = !open"
                            :aria-expanded="open ? 'true' : 'false'"
                        @class([
                            'w-full flex items-center justify-between gap-3 px-4 py-3 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                            $activeClasses => $isRoute('services'),
                            $inactiveClasses => ! $isRoute('services'),
                        ])>
                    <span class="flex items-center gap-3">
                        <x-icon name="shield-check" class="w-5 h-5"/>
                        <span>Services</span>
                    </span>
                        <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                x-bind:class="open && 'rotate-180'"/>
                    </button>
                    <div x-show="open" x-collapse x-cloak>
                        <div class="pl-10 pr-2 py-1 space-y-0.5">
                            <a href="{{ route('services') }}#standard" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">Standard
                                Units</a>
                            <a href="{{ route('services') }}#deluxe" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">Deluxe
                                Flushable</a>
                            <a href="{{ route('services') }}#ada" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">ADA
                                Accessible</a>
                            <a href="{{ route('services') }}#luxury" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">Luxury
                                Trailers</a>
                            <a href="{{ route('services') }}#shower" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">Portable
                                Showers</a>
                            <a href="{{ route('services') }}#construction" @click="mobileMenu = false"
                               class="block px-3 py-2.5 text-sm text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition min-h-[44px]">Construction
                                Packages</a>
                            <a href="{{ route('services') }}" @click="mobileMenu = false"
                               class="flex items-center gap-1 px-3 py-2.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 rounded-lg transition min-h-[44px]">
                                View all services
                                <x-icon name="arrow-right" class="w-3.5 h-3.5"/>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <a href="{{ route('pricing') }}"
                   @click="mobileMenu = false"
                   @class([
                       'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                       $activeClasses => $isRoute('pricing'),
                       $inactiveClasses => ! $isRoute('pricing'),
                   ])
                   @if($isRoute('pricing')) aria-current="page" @endif>
                    <x-icon name="currency-dollar" class="w-5 h-5"/>
                    <span>Pricing</span>
                </a>

                {{-- Locations --}}
                <a href="{{ route('locations') }}"
                   @click="mobileMenu = false"
                   @class([
                       'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                       $activeClasses => $isRoute('locations'),
                       $inactiveClasses => ! $isRoute('locations'),
                   ])
                   @if($isRoute('locations')) aria-current="page" @endif>
                    <x-icon name="map-pin" class="w-5 h-5"/>
                    <span>All Locations</span>
                </a>

                {{-- Blog --}}
                <a href="{{ route('blog.index') }}"
                   @click="mobileMenu = false"
                   @class([
                       'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                       $activeClasses => $isBlog,
                       $inactiveClasses => ! $isBlog,
                   ])
                   @if($isBlog) aria-current="page" @endif>
                    <x-icon name="calendar" class="w-5 h-5"/>
                    <span>Blog</span>
                </a>

                {{-- About --}}
                <a href="{{ route('about') }}"
                   @click="mobileMenu = false"
                   @class([
                       'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                       $activeClasses => $isRoute('about'),
                       $inactiveClasses => ! $isRoute('about'),
                   ])
                   @if($isRoute('about')) aria-current="page" @endif>
                    <x-icon name="users" class="w-5 h-5"/>
                    <span>About</span>
                </a>

                {{-- Divider --}}
                <div class="my-3 border-t border-slate-100 mx-4"></div>

                {{-- Secondary / legal links --}}
                <div class="px-6 pb-2 space-y-0.5 flex gap-3">
                    <a href="{{ route('privacy') }}" @click="mobileMenu = false"
                       class="block py-2 text-xs text-slate-500 hover:text-slate-700 transition">Privacy Policy</a>
                    <a href="{{ route('terms') }}" @click="mobileMenu = false"
                       class="block py-2 text-xs text-slate-500 hover:text-slate-700 transition">Terms of Service</a>
                </div>
            </nav>
        </aside>
    </div>
</header>

{{-- Page Content --}}
<main id="main" tabindex="-1" class="pt-0" itemscope itemtype="https://schema.org/LocalBusiness">
    <meta itemprop="name" content="{{ $domain?->business_name ?? 'Potty Direct' }}">
    <meta itemprop="telephone" content="{{ $phoneRaw }}">
    <meta itemprop="priceRange" content="$$-$$$">
    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
        <meta itemprop="addressLocality" content="{{ $cityAddress ?? ($topCities[0]['name'] ?? 'Dallas') }}">
        <meta itemprop="addressRegion" content="{{ $stateCodeLocal ?? ($topCities[0]['state']['code'] ?? 'TX') }}">
        <meta itemprop="postalCode" content="{{ $postalCode ?? ($topCities[0]['zip_code'] ?? '75201') }}">
        <meta itemprop="addressCountry" content="US">
    </div>
    <div itemprop="geo" itemscope itemtype="https://schema.org/GeoCoordinates">
        <meta itemprop="latitude" content="{{ $latitude ?? 32.7767 }}">
        <meta itemprop="longitude" content="{{ $longitude ?? -96.7970 }}">
    </div>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-slate-900 text-slate-300 pt-12 md:pt-16 pb-6 md:pb-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        {{-- Top Grid --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 pb-10 md:pb-12 border-b border-slate-700/60">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-5">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                        <span class="text-white font-extrabold text-lg leading-none tracking-tight">
                            {{ strtoupper(substr($domain?->business_name ?? 'PD', 0, 1)) }}{{ strtoupper(substr(explode(' ', $domain?->business_name ?? 'Potty Direct')[1] ?? 'D', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <div class="text-white font-bold text-sm">{{ $domain?->business_name ?? 'Potty Direct' }}</div>
                        <div
                            class="text-emerald-400 text-xs font-medium">{{ $domain?->primary_service ?? 'Portable Restrooms' }}</div>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed mb-6">
                    Your trusted partner for clean, affordable portable restroom rentals. Serving cities nationwide with
                    same-day delivery available.
                </p>
                <div class="text-sm text-slate-400" itemscope itemtype="https://schema.org/PostalAddress">
                    <div itemprop="name">{{ $domain?->business_name ?? 'Potty Direct' }}</div>
                    <div itemprop="addressLocality">{{ $cityAddress ?? ($topCities[0]['name'] ?? 'Dallas') }}</div>
                    <div><span
                            itemprop="addressRegion">{{ $stateCodeLocal ?? ($topCities[0]['state']['code'] ?? 'TX') }}</span>
                        <span itemprop="postalCode">{{ $postalCode ?? ($topCities[0]['zip_code'] ?? '75201') }}</span>
                    </div>
                    <div itemprop="addressCountry">USA</div>
                </div>
                <div class="flex items-center gap-3">
                    @if($domain?->google_business_url)
                        <a href="{{ $domain->google_business_url }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition"
                           aria-label="Google Business Profile">
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </a>
                    @endif
                    <a href="https://youtube.com/@pottydirect"
                       class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition"
                       aria-label="Youtube">
                        <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21.582 6.186c-.23-.86-.908-1.538-1.768-1.768C18.254 4 12 4 12 4s-6.254 0-7.814.418c-.86.23-1.538.908-1.768 1.768C2 7.746 2 12 2 12s0 4.254.418 5.814c.23.86.908 1.538 1.768 1.768C5.746 20 12 20 12 20s6.254 0 7.814-.418c.86-.23 1.538-.908 1.768-1.768C22 16.254 22 12 22 12s0-4.254-.418-5.814zM10 15.464V8.536L16 12l-6 3.464z"/>
                        </svg>
                    </a>
                    <a href="#"
                       class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition"
                       aria-label="Facebook">
                        <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="#"
                       class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition"
                       aria-label="Instagram">
                        <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="#"
                       class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition"
                       aria-label="LinkedIn">
                        <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Services</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('services') }}#standard"
                           class="text-sm text-slate-400 hover:text-white transition">Standard Porta Potties</a></li>
                    <li><a href="{{ route('services') }}#deluxe"
                           class="text-sm text-slate-400 hover:text-white transition">Deluxe Flushable Units</a></li>
                    <li><a href="{{ route('services') }}#ada"
                           class="text-sm text-slate-400 hover:text-white transition">ADA Accessible Units</a></li>
                    <li><a href="{{ route('services') }}#luxury"
                           class="text-sm text-slate-400 hover:text-white transition">Luxury Restroom Trailers</a></li>
                    <li><a href="{{ route('services') }}#handwash"
                           class="text-sm text-slate-400 hover:text-white transition">Hand Wash Stations</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-sm text-slate-400 hover:text-white transition">Pricing
                            Guide</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Company</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-slate-400 hover:text-white transition">About
                            Us</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-slate-400 hover:text-white transition">Blog
                            & Resources</a></li>
                    <li><a href="{{ route('locations') }}" class="text-sm text-slate-400 hover:text-white transition">All
                            Locations</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm text-slate-400 hover:text-white transition">Privacy
                            Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-sm text-slate-400 hover:text-white transition">Terms
                            of Service</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Contact</h4>
                <div class="space-y-4">
                    <a href="tel:{{ $phoneRaw }}" data-tracking-label="footer"
                       class="flex items-center gap-3 text-sm text-slate-400 hover:text-white transition group">
                        <div
                            class="w-9 h-9 bg-slate-800 group-hover:bg-emerald-600/20 rounded-lg flex items-center justify-center transition">
                            <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-white font-semibold">{{ $phoneDisplay }}</div>
                            <div class="text-xs text-slate-500">Tap to call</div>
                        </div>
                    </a>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <div class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center">
                            <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-white">{{ config('contact.hours') }}</div>
                            <div class="text-xs text-slate-500">Eastern Time</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <div class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center">
                            <svg aria-hidden="true" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </div>
                        <div>info@pottydirect.com</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-6 md:pt-8 flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">
            <p class="text-xs sm:text-sm text-slate-500 text-center sm:text-left">
                &copy; {{ date('Y') }} {{ $domain?->business_name ?? 'Potty Direct' }}. All rights reserved.
            </p>
            <div class="flex items-center gap-3 sm:gap-6 text-xs sm:text-sm text-slate-500">
                <a href="/sitemap.xml" class="hover:text-white transition">Sitemap</a>
                <span class="text-slate-700">|</span>
                <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy</a>
                <span class="text-slate-700">|</span>
                <a href="{{ route('terms') }}" class="hover:text-white transition">Terms</a>
            </div>
        </div>
    </div>
</footer>

{{-- Mobile Sticky CTA - Enhanced with pulse --}}
<div class="fixed left-4 right-4 md:hidden z-50" style="bottom: calc(1rem + env(safe-area-inset-bottom, 0px));">
    <a href="tel:{{ $phoneRaw }}"
       data-tracking-label="sticky"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98] min-h-[44px]">
        <svg aria-hidden="true" class="w-6 h-6 animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — {{ $phoneDisplay }}</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@include('components.phone-tracker')
@include('components.exit-intent-popup', ['title' => 'Get a Free Quote Today!'])
@stack('scripts')
</body>
</html>
