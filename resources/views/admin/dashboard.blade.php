@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Today's Calls</span>
                <span class="text-2xl">📞</span>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ number_format($todayStats['total_calls']) }}</div>
            <div class="text-sm text-green-600 mt-1">{{ $todayStats['qualified_calls'] }} qualified</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Today's Revenue</span>
                <span class="text-2xl">💰</span>
            </div>
            <div class="text-3xl font-bold text-green-600">${{ number_format($todayStats['revenue'], 2) }}</div>
            <div class="text-sm text-gray-500 mt-1">Profit: ${{ number_format($todayStats['profit'], 2) }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">This Month</span>
                <span class="text-2xl">📅</span>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ number_format($monthStats['total_calls']) }}</div>
            <div class="text-sm text-green-600 mt-1">${{ number_format($monthStats['revenue'], 2) }} revenue</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-500">Qualification Rate</span>
                <span class="text-2xl">📊</span>
            </div>
            <div class="text-3xl font-bold text-blue-600">{{ $todayStats['qualification_rate'] }}%</div>
            <div class="text-sm text-gray-500 mt-1">Avg duration: {{ round($todayStats['avg_duration'] / 60, 1) }} min</div>
        </div>
    </div>

    {{-- Resource Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.cities.index') }}" class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($resourceStats['active_cities']) }}</div>
            <div class="text-sm text-gray-500">Active Cities</div>
        </a>
        <a href="{{ route('admin.service-pages.index') }}" class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition text-center">
            <div class="text-2xl font-bold text-purple-600">{{ number_format($resourceStats['published_pages']) }}</div>
            <div class="text-sm text-gray-500">Published Pages</div>
        </a>
        <a href="{{ route('admin.phone-numbers.index') }}" class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition text-center">
            <div class="text-2xl font-bold text-green-600">{{ number_format($resourceStats['active_numbers']) }}</div>
            <div class="text-sm text-gray-500">Active Numbers</div>
        </a>
        <a href="{{ route('admin.buyers.index') }}" class="bg-white rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition text-center">
            <div class="text-2xl font-bold text-orange-600">{{ number_format($resourceStats['active_buyers']) }}</div>
            <div class="text-sm text-gray-500">Active Buyers</div>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Recent Calls --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-gray-800">Recent Calls</h2>
                <a href="{{ route('admin.calls.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Caller</th>
                            <th class="px-6 py-3">City</th>
                            <th class="px-6 py-3">Buyer</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentCalls as $call)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-mono text-xs">{{ $call->caller_number }}</td>
                                <td class="px-6 py-3">{{ $call->city?->name ?? '—' }}</td>
                                <td class="px-6 py-3">{{ $call->buyer?->company_name ?? '—' }}</td>
                                <td class="px-6 py-3">{!! $call->status_badge !!}</td>
                                <td class="px-6 py-3">{{ $call->duration_formatted }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No calls yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Cities --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Top Cities This Month</h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse($topCities as $cityStat)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-sm text-gray-800">{{ $cityStat->city?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $cityStat->call_count }} calls</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-green-600">${{ number_format($cityStat->total_revenue, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-4">No data yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
