@php
    $domain = $domain ?? $currentDomain ?? \App\Models\Domain::current();
    $businessName = $domain?->business_name ?? 'Plumbing Pro';
    $primaryService = $domain?->primary_service ?? 'plumbing services';
    $phone = domain_phone_display();
    $phoneRaw = domain_phone_raw();
    $domainColors = [
        'primary' => $domain?->primary_color ?? '#2563eb',
        'secondary' => $domain?->secondary_color ?? '#ea580c',
    ];
    $domainEmail = $domain?->email ?? 'info@plumbingpro.com';
    $domainUrl = $domain?->website_url ?? url('/');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $businessName . ' | ' . ucfirst($primaryService) . ' Services')</title>

    <meta name="description" content="@yield('meta_description', $businessName . ' offers professional ' . $primaryService . ' across the United States. Call ' . $phone . ' for fast, reliable service.')">

    <meta name="keywords" content="{{ $domain?->getSecondaryKeywordsFormatted() ?: 'plumber, plumbing, plumbing services, emergency plumber, drain cleaning, pipe repair, water heater, sewer line, leak detection' }}">

    <meta property="og:title" content="@yield('og_title', $businessName . ' | ' . ucfirst($primaryService) . ' Services')">
    <meta property="og:description" content="@yield('og_description', $businessName . ' offers professional ' . $primaryService . ' across the United States.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $businessName }}">

    <link rel="canonical" href="@yield('canonical', url()->current())" />

    @stack('schema')

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $businessName }}",
        "url": "{{ url('/') }}",
        "description": "{{ $businessName }} offers professional {{ $primaryService }} across the United States.",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/') }}?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    @env('local')
        <meta name="robots" content="noindex">
    @else
        <meta name="robots" content="@yield('robots', 'index, follow')">
    @endenv

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white text-slate-900 {{ auth()->check() ? 'pt-16' : '' }}"
      x-data="{ mobileMenu: false, servicesOpen: false }"
      x-effect="document.body.style.overflow = mobileMenu ? 'hidden' : ''"
      @keydown.escape.window="mobileMenu = false; servicesOpen = false">

    {{-- Skip to content --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Skip to content
    </a>

    {{-- Top Bar: Emergency Banner --}}
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm py-1.5 px-4 text-center font-medium">
        <span class="inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <strong class="hidden sm:inline">24/7 Emergency Plumbing:</strong>
            <a href="tel:{{ $phoneRaw }}" class="font-bold hover:underline whitespace-nowrap" data-tracking-label="emergency-banner">{{ $phone }}</a>
        </span>
    </div>

    {{-- Navigation --}}
    <header id="header" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16 lg:h-18">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group flex-shrink-0 my-1">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-base sm:text-lg shadow-lg shadow-blue-600/30 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 6L9 2l7 4 6-3v14l-6 3-7-4-6 3V6z"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-sm font-bold text-slate-800 leading-tight">{{ $businessName }}</span>
                        <span class="text-xs text-blue-600 font-medium leading-tight">{{ ucfirst($primaryService) }}</span>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden lg:flex items-center gap-1">
                    {{-- Services Dropdown --}}
                    <div class="relative" @click.away="servicesOpen = false">
                        <button type="button"
                                @click="servicesOpen = !servicesOpen"
                                :aria-expanded="servicesOpen ? 'true' : 'false'"
                                aria-haspopup="true"
                                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all flex items-center gap-1">
                            Services
                            <svg aria-hidden="true" class="w-4 h-4 transition-transform"
                                 :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="servicesOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                             class="absolute top-full left-0 pt-2 z-50" style="display: none;">
                            <div class="bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-100 py-2 w-64">
                                <a href="{{ route('services') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-blue-600 font-medium hover:bg-blue-50 transition border-b border-slate-100 min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">View All Services</div>
                                        <div class="text-xs text-slate-400">Complete plumbing service listing</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#emergency"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Emergency Plumbing</div>
                                        <div class="text-xs text-slate-400">24/7 emergency service</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#drain-cleaning"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Drain Cleaning</div>
                                        <div class="text-xs text-slate-400">Clogged drain repair</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#water-heater"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Water Heater Services</div>
                                        <div class="text-xs text-slate-400">Repair & installation</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#sewer-line"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Sewer & Drain</div>
                                        <div class="text-xs text-slate-400">Sewer line repair</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#leak-detection"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Leak Detection</div>
                                        <div class="text-xs text-slate-400">Hidden leak repair</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}#general"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition border-t border-slate-100 min-h-[44px]">
                                    <svg class="w-5 h-5 flex-shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">General Plumbing</div>
                                        <div class="text-xs text-slate-400">Repairs & installations</div>
                                    </div>
                                </a>
                                <a href="{{ route('services') }}"
                                   class="flex items-center gap-1 px-4 py-3 text-sm font-medium text-blue-600 hover:text-blue-700 rounded-lg transition justify-center border-t border-slate-100 min-h-[44px]">
                                    View all plumbing services
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Locations --}}
                    <a href="{{ route('locations') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        Locations
                    </a>

                    {{-- Pricing --}}
                    <a href="{{ route('pricing') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        Pricing
                    </a>

                    {{-- Blog --}}
                    <a href="{{ route('blog.index') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        Blog
                    </a>

                    {{-- About --}}
                    <a href="{{ route('about') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        About
                    </a>
                </nav>

                {{-- Desktop Search Bar --}}
                <div class="hidden md:flex items-center flex-1 max-w-xs mx-4 lg:mx-6">
                    <div class="relative w-full">
                        <input type="text" id="header-search" data-locations-url="{{ route('locations') }}"
                               placeholder="Search city or zip..."
                               class="w-full bg-slate-100 hover:bg-slate-50 border border-slate-200 focus:border-blue-300 focus:ring-2 focus:ring-blue-100 rounded-full py-2 pl-10 pr-4 text-sm transition outline-none">
                        <svg aria-hidden="true" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- CTA + Hamburger --}}
                <div class="flex items-center gap-1 sm:gap-2">
                    <a href="tel:{{ $phoneRaw }}" data-tracking-label="header"
                       class="flex items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white text-xs sm:text-sm font-bold py-2 px-2.5 sm:px-4 rounded-full shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 transition-all hover:scale-105 ring-2 ring-orange-400/30 min-h-[44px]"
                       aria-label="Call {{ $phone }}">
                        <svg aria-hidden="true" class="w-3.5 h-3.5 sm:w-4 sm:h-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span class="whitespace-nowrap">{{ $phone }}</span>
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
            $activeClasses = 'bg-blue-50 text-blue-700 border-blue-500';
            $inactiveClasses = 'text-slate-700 hover:bg-slate-50 border-transparent';
        @endphp
        <div class="lg:hidden fixed inset-0 z-[9999]"
             x-cloak
             :class="mobileMenu ? 'block' : 'hidden'">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300"
                 :class="mobileMenu ? 'opacity-100' : 'opacity-0'"
                 @click="mobileMenu = false"></div>

            {{-- Sidebar --}}
            <aside class="fixed inset-y-0 right-0 w-[85vw] max-w-sm bg-white shadow-2xl flex flex-col transition-transform duration-300 ease-out will-change-transform"
                   :class="mobileMenu ? 'translate-x-0' : 'translate-x-full'">
                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-2 border-b border-slate-200 flex-shrink-0">
                    <a href="{{ route('home') }}" @click="mobileMenu = false" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 6L9 2l7 4 6-3v14l-6 3-7-4-6 3V6z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-800 leading-tight">{{ $businessName }}</div>
                            <div class="text-[10px] text-blue-600 font-medium leading-tight">{{ ucfirst($primaryService) }}</div>
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
                <nav class="flex-1 overflow-y-auto py-2" aria-label="Primary navigation">
                    {{-- Search in mobile --}}
                    <div class="px-4 pb-3">
                        <div class="relative">
                            <input type="text" id="mobile-header-search" data-locations-url="{{ route('locations') }}"
                                   placeholder="Search city or zip..."
                                   class="w-full bg-slate-100 border border-slate-200 focus:border-blue-300 focus:ring-2 focus:ring-blue-100 rounded-lg py-2.5 pl-10 pr-4 text-sm transition outline-none">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Home --}}
                    <a href="{{ route('home') }}"
                       @click="mobileMenu = false"
                       @class([
                           'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                           $activeClasses => $isRoute('home'),
                           $inactiveClasses => ! $isRoute('home'),
                       ])
                       @if($isRoute('home')) aria-current="page" @endif>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Home</span>
                    </a>

                    {{-- Services accordion --}}
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
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Services</span>
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 x-bind:class="open && 'rotate-180'">
                                <path d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse x-cloak>
                            <div class="pl-10 pr-2 py-1 space-y-0.5">
                                <a href="{{ route('services') }}#emergency" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Emergency Plumbing</a>
                                <a href="{{ route('services') }}#drain-cleaning" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Drain Cleaning</a>
                                <a href="{{ route('services') }}#water-heater" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Water Heater Services</a>
                                <a href="{{ route('services') }}#sewer-line" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Sewer & Drain</a>
                                <a href="{{ route('services') }}#leak-detection" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Leak Detection</a>
                                <a href="{{ route('services') }}#pipe-repair" @click="mobileMenu = false"
                                   class="block px-3 py-2.5 text-sm text-slate-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition min-h-[44px]">Pipe Repair</a>
                                <a href="{{ route('services') }}" @click="mobileMenu = false"
                                   class="flex items-center gap-1 px-3 py-2.5 text-sm font-medium text-blue-600 hover:text-blue-700 rounded-lg transition min-h-[44px]">
                                    View all services
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
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
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
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
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>All Locations</span>
                    </a>

                    {{-- Blog --}}
                    <a href="{{ route('blog.index') }}"
                       @click="mobileMenu = false"
                       @class([
                           'flex items-center gap-3 px-4 py-3 mx-2 my-0.5 text-base font-semibold rounded-lg transition border-l-4 pl-3',
                           $activeClasses => $isRoute('blog.*'),
                           $inactiveClasses => ! $isRoute('blog.*'),
                       ])
                       @if($isRoute('blog.*')) aria-current="page" @endif>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
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
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>About Us</span>
                    </a>

                    {{-- Mobile CTA --}}
                    <div class="px-4 pt-3 pb-4">
                        <a href="tel:{{ $phoneRaw }}" @click="mobileMenu = false"
                           class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            <span>Call Now — Free Estimate</span>
                        </a>
                    </div>
                </nav>
            </aside>
        </div>
    </header>

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Company info --}}
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 6L9 2l7 4 6-3v14l-6 3-7-4-6 3V6z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">{{ $businessName }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed mb-4">
                        Professional {{ $primaryService }} for residential and commercial customers across the United States. Available 24/7 for emergency service.
                    </p>
                    <div class="space-y-2 text-sm">
                        <a href="tel:{{ $phoneRaw }}" class="flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>{{ $phone }}</span>
                        </a>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $domainEmail }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-white transition-colors">Our Services</a></li>
                        <li><a href="{{ route('locations') }}" class="hover:text-white transition-colors">Service Areas</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                {{-- Services --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Our Services</h3>
                    <ul class="space-y-2.5 text-sm">
                        @php
                            $footerServices = collect($domain?->getServiceTypes() ?: ['emergency', 'drain-cleaning', 'pipe-repair', 'water-heater', 'sewer-line', 'leak-detection'])->take(6);
                        @endphp
                        @foreach($footerServices as $serviceType)
                            <li>
                                <a href="{{ route('services') }}#{{ $serviceType }}" class="hover:text-white transition-colors">
                                    {{ $domain?->getServiceTypeLabel($serviceType) ?: ucfirst(str_replace('-', ' ', $serviceType)) }}
                                </a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('services') }}" class="text-blue-400 hover:text-blue-300 transition-colors font-medium">View All Services →</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li>
                            <a href="tel:{{ $phoneRaw }}" class="flex items-center gap-2 hover:text-white transition-colors">
                                <svg class="w-4 h-4 text-blue-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ $phone }}
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ $domainEmail }}" class="flex items-center gap-2 hover:text-white transition-colors">
                                <svg class="w-4 h-4 text-blue-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $domainEmail }}
                            </a>
                        </li>
                        <li class="pt-3">
                            <a href="tel:{{ $phoneRaw }}" class="inline-block bg-orange-500 hover:bg-orange-400 text-white text-sm font-bold py-2.5 px-5 rounded-full transition-colors">
                                Call for Free Estimate
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom --}}
            <div class="mt-10 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $businessName }}. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                    @if($domain?->website_url)
                        <a href="{{ $domain->website_url }}" class="hover:text-white transition-colors" target="_blank" rel="noopener">Visit Our Website</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Sticky CTA --}}
    <div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
        <a href="tel:{{ $phoneRaw }}" data-tracking-label="mobile-sticky-cta"
           class="flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <span>Call Now — Free Estimate</span>
        </a>
    </div>
    <div class="h-20 md:hidden"></div>

    {{-- Smooth scroll for anchor links --}}
    <script>
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a[href^="#"]');
            if (target) {
                const id = target.getAttribute('href').slice(1);
                const el = document.getElementById(id);
                if (el) {
                    e.preventDefault();
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
