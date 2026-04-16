<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ $currentDomain?->business_name ?? $currentDomain?->name ?? 'Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- Mobile Backdrop --}}
    <div x-data="{ mobileOpen: false }"
         @toggle-mobile-menu.window="mobileOpen = !mobileOpen"
         @close-mobile-menu.window="mobileOpen = false"
         @keydown.escape.window="mobileOpen = false">
        <div x-show="mobileOpen"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 z-50 bg-black/60 md:hidden">
        </div>

        {{-- Mobile Sidebar --}}
        <aside x-show="mobileOpen"
               x-transition:enter="transition-transform duration-300 ease-out"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-200 ease-in"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               style="position: fixed; top: 0; left: 0; width: 288px; max-width: 85vw; height: 100vh; z-index: 50; display: flex; flex-direction: column; background-color: #0f172a; color: white;"
               x-cloak>
            <div style="flex-shrink: 0; padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.5); display: flex; align-items: center; justify-content: space-between;">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br rounded-lg flex items-center justify-center text-xl shadow-lg" style="background-color: {{ $domain?->primary_color ?? '#22C55E' }}">🚽</div>
                    <div>
                        <div class="font-bold text-sm leading-tight">{{ $domain?->business_name ?? $domain?->name ?? 'Admin' }}</div>
                        <div class="text-xs text-slate-400">Admin Panel</div>
                    </div>
                </a>
                <button @click="$dispatch('close-mobile-menu')" class="p-2 hover:bg-slate-800 rounded-lg text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav style="flex: 1 1 0%; overflow-y: auto; padding-top: 1rem; padding-bottom: 1rem;">
                <div class="px-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Overview</div>
                <a href="{{ route('admin.dashboard') }}"
                   @click="$dispatch('close-mobile-menu')"
                   class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>

                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Domain Data</div>

                <a href="{{ route('admin.states.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.states.index') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span>States</span>
                </a>

                <a href="{{ route('admin.cities.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.cities.index') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span>Cities</span>
                </a>

                <a href="{{ route('admin.service-pages.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.service-pages.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Service Pages</span>
                </a>

                <a href="{{ route('admin.buyers.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.buyers.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Buyers</span>
                </a>

                <a href="{{ route('admin.phone-numbers.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.phone-numbers.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>Phone Numbers</span>
                </a>

                <a href="{{ route('admin.invoices.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.invoices.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span>Invoices</span>
                </a>

                <a href="{{ route('admin.blog-posts.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.blog-posts.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Blog Posts</span>
                </a>

                <a href="{{ route('admin.blog-categories.index') }}" @click="$dispatch('close-mobile-menu')" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.blog-categories.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Blog Categories</span>
                </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analytics</div>

            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Reports</span>
            </a>

            <a href="{{ route('admin.calls.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.calls.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>All Calls</span>
            </a>

            <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.logs.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>System Logs</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">AI</div>

            <a href="{{ route('admin.api-keys.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.api-keys.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>API Keys</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</div>

            <a href="{{ route('admin.domains.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.domains.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                <span>Domains</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</div>

            <a href="{{ route('admin.global.states.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.global.states.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span>Global States</span>
            </a>

            <a href="{{ route('admin.global.cities.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.global.cities.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Global Cities</span>
            </a>
        </nav>

            <div style="flex-shrink: 0; padding: 1rem; border-top: 1px solid rgba(51, 65, 85, 0.5);">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Back to Site</span>
                </a>
            </div>
        </aside>
    </div>

    {{-- Desktop Sidebar --}}
    <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="p-4 border-b border-slate-700/50">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br rounded-lg flex items-center justify-center text-xl shadow-lg" style="background-color: {{ $currentDomain->primary_color ?? '#22C55E' }}">🚽</div>
                <div>
                    <div class="font-bold text-sm leading-tight">{{ $currentDomain->business_name ?? $currentDomain->name ?? 'Admin' }}</div>
                    <div class="text-xs text-slate-400">Admin Panel</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <div class="px-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Domain Data</div>

            <a href="{{ route('admin.states.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.states.index') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span>States</span>
            </a>

            <a href="{{ route('admin.cities.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.cities.index') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Cities</span>
            </a>

            <a href="{{ route('admin.service-pages.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.service-pages.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Service Pages</span>
            </a>

            <a href="{{ route('admin.buyers.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.buyers.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Buyers</span>
            </a>

            <a href="{{ route('admin.phone-numbers.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.phone-numbers.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <span>Phone Numbers</span>
            </a>

            <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.invoices.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>Invoices</span>
            </a>

            <a href="{{ route('admin.blog-posts.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.blog-posts.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Blog Posts</span>
            </a>

            <a href="{{ route('admin.blog-categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.blog-categories.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span>Blog Categories</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analytics</div>

            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Reports</span>
            </a>

            <a href="{{ route('admin.calls.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.calls.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>All Calls</span>
            </a>

            <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.logs.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>System Logs</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</div>

            <a href="{{ route('admin.domains.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.domains.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                <span>Domains</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</div>

            <a href="{{ route('admin.global.states.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.global.states.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span>Global States</span>
            </a>

            <a href="{{ route('admin.global.cities.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.global.cities.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Global Cities</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">AI</div>

            <a href="{{ route('admin.api-keys.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm mx-2 rounded-lg {{ request()->routeIs('admin.api-keys.*') ? 'bg-green-600/20 text-green-400' : 'text-slate-300 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>API Keys</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/50">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Back to Site</span>
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30 flex-shrink-0">
            <div class="px-4 md:px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button @click="$dispatch('toggle-mobile-menu')" class="md:hidden p-2 hover:bg-gray-100 rounded-lg text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-lg md:text-xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3 md:gap-4 flex-shrink-0">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            @if($currentDomain)
                            style="background-color: {{ $currentDomain->primary_color }}10; border-color: {{ $currentDomain->primary_color }}30; color: {{ $currentDomain->primary_color }};"
                            @endif
                            class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-sm font-medium transition">
                            <div class="w-3 h-3 rounded-full @if($currentDomain) ring-2 ring-offset-1" style="background-color: {{ $currentDomain->primary_color }}; ring-color: {{ $currentDomain->primary_color }}20; @endif"></div>
                            <span class="hidden sm:inline">{{ $currentDomain->name ?? 'Select Domain' }}</span>
                            <span class="sm:hidden">{{ $currentDomain->name ?? 'Domain' }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Switch Domain</div>
                            </div>
                            @php
                                $domains = \App\Models\Domain::all();
                            @endphp
                            @foreach($domains as $domain)
                                <form method="POST" action="{{ route('admin.domains.switch', $domain) }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $currentDomain && $currentDomain->id === $domain->id ? 'bg-gray-50' : 'text-gray-700' }}">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $domain->primary_color }};">
                                            {{ strtoupper(substr($domain->name, 0, 2)) }}
                                        </div>
                                        <div class="flex-1 text-left">
                                            <div class="font-medium text-sm">{{ $domain->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $domain->domain }}</div>
                                        </div>
                                        @if($currentDomain && $currentDomain->id === $domain->id)
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center" style="background-color: {{ $domain->primary_color }};">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <a href="{{ route('admin.domains.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-500 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Manage All Domains
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:flex items-center gap-4 px-4 py-2 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ $todayCalls ?? 0 }}</div>
                            <div class="text-xs text-gray-400">Today's Calls</div>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">${{ number_format($todayRevenue ?? 0, 2) }}</div>
                            <div class="text-xs text-gray-400">Today's Revenue</div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 hover:bg-gray-100 rounded-lg transition">
                            <div class="w-8 h-8 md:w-9 md:h-9 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</div>
                                <div class="text-xs text-gray-400">Admin</div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-2 pt-2">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 min-w-0">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
