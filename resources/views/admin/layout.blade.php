<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Porta Potty Rental</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col">
        <div class="p-4 border-b border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-white">
                <span class="text-2xl">🚽</span>
                <div>
                    <div class="font-bold text-sm">Porta Potty Rental</div>
                    <div class="text-xs text-gray-400">Admin Panel</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📊 <span>Dashboard</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</div>

            <a href="{{ route('admin.cities.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.cities.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                🏙️ <span>Cities</span>
            </a>

            <a href="{{ route('admin.service-pages.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.service-pages.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📄 <span>Service Pages</span>
            </a>

            <a href="{{ route('admin.buyers.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.buyers.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                🛒 <span>Buyers</span>
            </a>

            <a href="{{ route('admin.phone-numbers.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.phone-numbers.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📞 <span>Phone Numbers</span>
            </a>

            <a href="{{ route('admin.invoices.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.invoices.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                💰 <span>Invoices</span>
            </a>

            <a href="{{ route('admin.blog-posts.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.blog-posts.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📝 <span>Blog Posts</span>
            </a>

            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Analytics</div>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.reports') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📈 <span>Reports</span>
            </a>

            <a href="{{ route('admin.calls.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-gray-800 transition {{ request()->routeIs('admin.calls.*') ? 'bg-gray-800 border-r-2 border-green-500' : '' }}">
                📞 <span>All Calls</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
                ← Back to Site
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ now()->format('M j, Y') }}</span>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
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
