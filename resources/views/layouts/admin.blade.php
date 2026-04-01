<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Porta Potty Dashboard</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white min-h-screen fixed left-0 top-0
                      overflow-y-auto z-40 hidden lg:block">
        {{-- Logo --}}
        <div class="p-5 border-b border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl">🚽</span>
                <span class="font-bold text-lg">PPR Admin</span>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>📊</span> Dashboard
            </a>

            <a href="{{ route('admin.calls.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.calls.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>📞</span> Call Logs
                @php $todayCalls = \App\Models\CallLog::today()->count(); @endphp
                @if($todayCalls > 0)
                    <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $todayCalls }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.cities.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.cities.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>🏙️</span> Cities
            </a>

            <a href="{{ route('admin.service-pages.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.service-pages.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>📄</span> Service Pages
            </a>

            <a href="{{ route('admin.blog-posts.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.blog-posts.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>✍️</span> Blog Posts
            </a>

            <a href="{{ route('admin.buyers.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.buyers.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>🤝</span> Buyers
            </a>

            <a href="{{ route('admin.phone-numbers.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.phone-numbers.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>📱</span> Phone Numbers
            </a>

            <a href="{{ route('admin.invoices.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>💵</span> Invoices
            </a>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}
                          transition">
                <span>📈</span> Reports
            </a>

            <div class="border-t border-gray-700 my-4"></div>

            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                          text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <span>🌐</span> View Site ↗
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm
                                   text-gray-400 hover:bg-red-600 hover:text-white transition">
                    <span>🚪</span> Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 lg:ml-64">
        {{-- Top Bar --}}
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4
                          flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    @yield('page_title', 'Dashboard')
                </h1>
                @hasSection('page_subtitle')
                    <p class="text-sm text-gray-500">@yield('page_subtitle')</p>
                @endif
            </div>

            <div class="flex items-center gap-4">
                {{-- Quick Stats --}}
                <div class="hidden md:flex items-center gap-6 text-sm">
                    @php
                        $quickCalls = \App\Models\CallLog::today()->billable()->count();
                        $quickRevenue = \App\Models\CallLog::today()->billable()->sum('payout');
                    @endphp
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">{{ $quickCalls }}</div>
                        <div class="text-xs text-gray-400">Today's Calls</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-600">${{ number_format($quickRevenue, 2) }}</div>
                        <div class="text-xs text-gray-400">Today's Revenue</div>
                    </div>
                </div>

                {{-- Bangladesh Time --}}
                <div class="text-xs text-gray-400 hidden lg:block">
                    🇧🇩 {{ now()->setTimezone('Asia/Dhaka')->format('h:i A') }}
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700
                        px-4 py-3 rounded-lg flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700
                        px-4 py-3 rounded-lg flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
