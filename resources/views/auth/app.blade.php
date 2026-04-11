<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Porta Potty Rental | Same Day Delivery | Construction & Event Toilets')</title>
    <meta name="description" content="@yield('meta_description', 'Need porta potty rental? Potty Direct offers same-day delivery of clean portable toilets for construction, events & weddings. Get your personalized quote today. Call '.phone_display().'!')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Custom Open Graph Image (can be overridden per page) --}}
    @section('og_image')
    <meta property="og:image" content="{{ url('/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Potty Direct - Portable Restroom Rental">
    <meta name="twitter:image" content="{{ url('/og-image.jpg') }}">
    <meta name="twitter:image:alt" content="Potty Direct - Portable Restroom Rental">
    @show

    {{-- Schema.org JSON-LD --}}
    @stack('schema')

    {{-- Open Graph / Social --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:site_name" content="Potty Direct">
    <meta property="og:locale" content="en_US">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    <meta name="twitter:site" content="@pottydirect">

    {{-- Hreflang for International SEO --}}
    <link rel="alternate" hreflang="en" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="en-US" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="x-default" href="@yield('canonical', url()->current())">

    {{-- Pagination SEO (for paginated pages) --}}
    @yield('pagination_headers')

    {{-- Additional SEO --}}
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="Potty Direct">
    <meta name="geo.region" content="US">
    <meta name="geo.placename" content="United States">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <meta name="theme-color" content="#10b981">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚽</text></svg>">

    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    {{-- Google Analytics 4 --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

{{-- Announcement Banner --}}
<div id="announcement-banner" class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-2 px-3 sm:px-4">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
        <span class="hidden sm:inline animate-pulse">🔥</span>
        <span class="font-medium text-center sm:text-left">Same-Day Delivery Available</span>
        <span class="hidden md:inline text-emerald-100">• Call for availability</span>
        <a href="tel:{{ phone_raw() }}" class="ml-0 sm:ml-2 bg-white hover:bg-emerald-50 text-emerald-600 hover:text-emerald-700 px-3 sm:px-3 py-1.5 sm:py-1 rounded-full font-bold transition text-sm sm:text-sm whitespace-nowrap shadow-sm inline-flex items-center">
            📞 {{ phone_display() }}
        </a>
    </div>
</div>

{{-- Header --}}
<header id="header" class="sticky top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14 sm:h-16 lg:h-18">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group flex-shrink-0 my-1">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
                    🚽
                </div>
                <div class="hidden sm:block">
                    <div class="text-sm font-bold text-slate-800 leading-tight">Potty Direct</div>
                    <div class="text-xs text-emerald-600 font-medium leading-tight">Portable Restrooms</div>
                </div>
            </a>

            {{-- Mobile Search Box (visible center on small screens) --}}
            <div class="flex-1 mx-2 md:hidden flex items-center">
                <div class="relative flex-1">
                    <input type="text" id="mobile-header-search" placeholder="Search city or zip..."
                           class="w-full bg-slate-100 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm transition outline-none focus:border-emerald-300 focus:ring-2 focus:ring-emerald-100">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                {{-- Mobile Search Results Dropdown --}}
                <div id="mobile-search-results" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 py-2 max-h-80 overflow-y-auto hidden z-[100] w-[90%] max-w-md">
                    <div class="px-4 py-3 text-sm text-slate-500 text-center">
                        Type a city name or zip code to search...
                    </div>
                </div>
            </div>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                {{-- Services Dropdown --}}
                <div class="relative group">
                    <button class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all flex items-center gap-1">
                        Services
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-100 py-2 w-56">
                            <a href="{{ route('services') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-emerald-600 font-medium hover:bg-emerald-50 transition border-b border-slate-100">
                                <span class="text-lg">📋</span>
                                <div>
                                    <div class="font-medium">View All Services</div>
                                    <div class="text-xs text-slate-400">Complete service listing</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#standard" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">🚻</span>
                                <div>
                                    <div class="font-medium">Standard Units</div>
                                    <div class="text-xs text-slate-400">Basic portable toilets</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#deluxe" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">🚿</span>
                                <div>
                                    <div class="font-medium">Deluxe Flushable</div>
                                    <div class="text-xs text-slate-400">With hand wash station</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#ada" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">♿</span>
                                <div>
                                    <div class="font-medium">ADA Accessible</div>
                                    <div class="text-xs text-slate-400">Wheelchair friendly</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#luxury" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">✨</span>
                                <div>
                                    <div class="font-medium">Luxury Trailers</div>
                                    <div class="text-xs text-slate-400">Premium restroom trailers</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#shower" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">🚿</span>
                                <div>
                                    <div class="font-medium">Portable Showers</div>
                                    <div class="text-xs text-slate-400">Hot & cold water units</div>
                                </div>
                            </a>
                            <a href="{{ route('services') }}#construction" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                <span class="text-lg">🏗️</span>
                                <div>
                                    <div class="font-medium">Construction Packages</div>
                                    <div class="text-xs text-slate-400">Bulk pricing for job sites</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Locations --}}
                <a href="{{ route('locations') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Locations
                </a>

                {{-- Pricing --}}
                <a href="{{ route('pricing') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Pricing
                </a>

                {{-- Blog --}}
                <a href="{{ route('blog.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    Blog
                </a>

                {{-- About --}}
                <a href="{{ route('about') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                    About
                </a>
            </nav>

            {{-- Desktop Search Bar --}}
            <div class="hidden md:flex items-center flex-1 max-w-xs mx-4 lg:mx-6">
                <div class="relative w-full">
                    <input type="text" id="header-search" placeholder="Search city or zip..."
                           class="w-full bg-slate-100 hover:bg-slate-50 border border-slate-200 focus:border-emerald-300 focus:ring-2 focus:ring-emerald-100 rounded-full py-2 pl-10 pr-4 text-sm transition outline-none">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    {{-- Search Results Dropdown --}}
                    <div id="search-results" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 py-2 max-h-80 overflow-y-auto hidden z-50">
                        <div class="px-4 py-3 text-sm text-slate-500 text-center">
                            Type a city name or zip code to search...
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="tel:{{ phone_raw() }}"
                   class="hidden sm:flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-bold py-2 px-3 sm:px-5 rounded-full shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all hover:scale-105">
                    <span>📞</span>
                    <span class="hidden lg:inline">{{ phone_display() }}</span>
                </a>

                {{-- Mobile Menu Toggle --}}
                <button id="mobile-menu-btn" type="button" class="lg:hidden p-2 h-auto min-h-[2.5rem] rounded-lg hover:bg-slate-100 transition flex items-center justify-center" aria-label="Menu">
                    <span id="menu-icon" class="flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </span>
                    <span id="menu-close-icon" class="hidden flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Full-Screen Menu --}}
    <div id="mobile-menu" class="lg:hidden fixed inset-x-0 top-14 sm:top-16 bg-white z-[9999] overflow-y-auto" style="display: none; height: calc(100vh - 3.5rem);">
        <div class="px-4 sm:px-6 pb-8">
            {{-- Mobile Nav Links --}}
            <nav class="space-y-2 mb-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">🏠</span> Home
                </a>
                <a href="{{ route('services') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">🚽</span> Services
                </a>
                <a href="{{ route('pricing') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">💰</span> Pricing
                </a>
                <a href="{{ route('locations') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">📍</span> All Locations
                </a>
                <a href="{{ route('blog.index') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">📝</span> Blog
                </a>
                <a href="{{ route('about') }}" class="flex items-center gap-3 px-4 py-3 text-lg font-medium text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                    <span class="text-2xl">ℹ️</span> About Us
                </a>
            </nav>

            {{-- Mobile CTA --}}
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white text-center">
                <h3 class="text-lg font-bold mb-2">Ready to Rent?</h3>
                <p class="text-emerald-100 text-sm mb-4">Call for instant pricing</p>
                <a href="tel:{{ phone_raw() }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-emerald-600 font-bold text-lg py-3 px-8 rounded-full hover:scale-105 transition shadow-lg w-full">
                    📞 {{ phone_display() }}
                </a>
            </div>
        </div>
    </div>
</header>

{{-- Page Content --}}
<main class="pt-0">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-slate-900 text-slate-300 pt-12 md:pt-16 pb-6 md:pb-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        {{-- Top Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 pb-10 md:pb-12 border-b border-slate-700/60">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg">🚽</div>
                    <div>
                        <div class="text-white font-bold text-sm">Potty Direct</div>
                        <div class="text-emerald-400 text-xs font-medium">Portable Restrooms</div>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed mb-6">
                    Your trusted partner for clean, affordable portable restroom rentals. Serving cities nationwide with same-day delivery available.
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition" aria-label="Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Services</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('services') }}#standard" class="text-sm text-slate-400 hover:text-white transition">Standard Porta Potties</a></li>
                    <li><a href="{{ route('services') }}#deluxe" class="text-sm text-slate-400 hover:text-white transition">Deluxe Flushable Units</a></li>
                    <li><a href="{{ route('services') }}#ada" class="text-sm text-slate-400 hover:text-white transition">ADA Accessible Units</a></li>
                    <li><a href="{{ route('services') }}#luxury" class="text-sm text-slate-400 hover:text-white transition">Luxury Restroom Trailers</a></li>
                    <li><a href="{{ route('services') }}#handwash" class="text-sm text-slate-400 hover:text-white transition">Hand Wash Stations</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-sm text-slate-400 hover:text-white transition">Pricing Guide</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Company</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-slate-400 hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-slate-400 hover:text-white transition">Blog & Resources</a></li>
                    <li><a href="{{ route('locations') }}" class="text-sm text-slate-400 hover:text-white transition">All Locations</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-sm text-slate-400 hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-sm text-slate-400 hover:text-white transition">Terms of Service</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5 uppercase tracking-wider">Contact</h4>
                <div class="space-y-4">
                    <a href="tel:{{ phone_raw() }}" class="flex items-center gap-3 text-sm text-slate-400 hover:text-white transition group">
                        <div class="w-9 h-9 bg-slate-800 group-hover:bg-emerald-600/20 rounded-lg flex items-center justify-center transition">
                            <span>📞</span>
                        </div>
                        <div>
                            <div class="text-white font-semibold">{{ phone_display() }}</div>
                            <div class="text-xs text-slate-500">Tap to call</div>
                        </div>
                    </a>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <div class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center">⏰</div>
                        <div>
                            <div class="text-white">{{ config('contact.hours') }}</div>
                            <div class="text-xs text-slate-500">Eastern Time</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-400">
                        <div class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center">📧</div>
                        <div>{{ config('contact.email') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-6 md:pt-8 flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">
            <p class="text-xs sm:text-sm text-slate-500 text-center sm:text-left">
                &copy; {{ date('Y') }} Potty Direct. All rights reserved.
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

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-emerald-500 to-emerald-600 md:hidden z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.15)] safe-area-bottom">
    <a href="tel:{{ phone_raw() }}"
       class="flex items-center justify-center gap-2 text-white font-bold text-base sm:text-lg py-3 sm:py-4">
        <span class="text-lg">📞</span>
        <span>Call Now — Free Quote</span>
    </a>
</div>
<div class="h-12 md:hidden safe-area-bottom"></div>

<script>
    // Header scroll effect - shrink on scroll
    const header = document.getElementById('header');
    let lastScroll = 0;
    let ticking = false;

    function updateHeader() {
        const currentScroll = window.scrollY;

        // Shrink header on scroll
        if (currentScroll > 50) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }

        lastScroll = currentScroll;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });

    // Mobile menu toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    menuBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        
        if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
            mobileMenu.style.display = 'block';
            if (menuIcon) menuIcon.classList.add('hidden');
            if (menuCloseIcon) menuCloseIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            mobileMenu.style.display = 'none';
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (menuCloseIcon) menuCloseIcon.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // Close mobile menu on link click
    mobileMenu?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.style.display = 'none';
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (menuCloseIcon) menuCloseIcon.classList.add('hidden');
            document.body.style.overflow = '';
        });
    });

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && (mobileMenu.style.display === 'block')) {
            mobileMenu.style.display = 'none';
            if (menuIcon) menuIcon.classList.remove('hidden');
            if (menuCloseIcon) menuCloseIcon.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // Call tracking
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click_to_call', {
                    event_category: 'engagement',
                    event_label: this.href,
                    page_path: window.location.pathname,
                });
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('header-search');
    const searchResults = document.getElementById('search-results');

    if (searchInput && searchResults) {
        let searchTimeout;

        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                // Simulate search - in production, this would be an API call
                searchResults.innerHTML = `
                    <div class="px-4 py-3 text-sm text-slate-500 flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Searching for "${query}"...
                    </div>
                `;
                searchResults.classList.remove('hidden');

                // Simulate finding results
                setTimeout(() => {
                    searchResults.innerHTML = `
                        <a href="{{ route('locations') }}?q=${query}" class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 transition border-b border-slate-50">
                            <span class="text-emerald-500">📍</span>
                            <div>
                                <div class="text-sm font-medium text-slate-700">${query}, TX</div>
                                <div class="text-xs text-slate-400">Texas • View all Texas locations</div>
                            </div>
                        </a>
                        <a href="{{ route('locations') }}?q=${query}" class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 transition">
                            <span class="text-emerald-500">📍</span>
                            <div>
                                <div class="text-sm font-medium text-slate-700">View all locations matching "${query}"</div>
                                <div class="text-xs text-slate-400">Browse cities</div>
                            </div>
                        </a>
                    `;
                }, 300);
            }, 300);
        });

        // Hide results on click outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // Handle enter key
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                if (query) {
                    window.location.href = `{{ route('locations') }}?q=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = `{{ route('locations') }}`;
                }
            }
        });
    }

    // Mobile Header Search functionality (uses separate mobile-search-results dropdown)
    const mobileHeaderSearchInput = document.getElementById('mobile-header-search');
    const mobileSearchResults = document.getElementById('mobile-search-results');
    
    if (mobileHeaderSearchInput && mobileSearchResults) {
        let searchTimeout;

        mobileHeaderSearchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            if (query.length < 2) {
                mobileSearchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                mobileSearchResults.innerHTML = `
                    <div class="px-4 py-3 text-sm text-slate-500 flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Searching for "${query}"...
                    </div>
                `;
                mobileSearchResults.classList.remove('hidden');

                setTimeout(() => {
                    mobileSearchResults.innerHTML = `
                        <a href="{{ route('locations') }}?q=${query}" class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 transition border-b border-slate-50">
                            <span class="text-emerald-500">📍</span>
                            <div>
                                <div class="text-sm font-medium text-slate-700">${query}, TX</div>
                                <div class="text-xs text-slate-400">Texas • View all Texas locations</div>
                            </div>
                        </a>
                        <a href="{{ route('locations') }}?q=${query}" class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 transition">
                            <span class="text-emerald-500">📍</span>
                            <div>
                                <div class="text-sm font-medium text-slate-700">View all locations matching "${query}"</div>
                                <div class="text-xs text-slate-400">Browse cities</div>
                            </div>
                        </a>
                    `;
                }, 300);
            }, 300);
        });

        // Hide results on click outside
        document.addEventListener('click', (e) => {
            if (!mobileHeaderSearchInput.contains(e.target) && !mobileSearchResults.contains(e.target)) {
                mobileSearchResults.classList.add('hidden');
            }
        });

        // Handle enter key
        mobileHeaderSearchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const query = mobileHeaderSearchInput.value.trim();
                if (query) {
                    window.location.href = `{{ route('locations') }}?q=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = `{{ route('locations') }}`;
                }
            }
        });
    }
</script>

@include('components.phone-tracker')
@include('components.exit-intent-popup', ['title' => 'Get a Free Quote Today!', 'message' => 'Call now and mention this popup for %DISCOUNT% off your first rental!', 'discount' => '10'])
@stack('scripts')
</body>
</html>
