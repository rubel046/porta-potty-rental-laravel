@extends('admin.layout')
@section('title', 'Reports')
@section('page-title', 'Analytics Reports')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Performance Reports</h2>
        <p class="text-sm text-gray-500">Track your call analytics and revenue metrics</p>
    </div>
    <form method="GET" class="flex gap-2 items-center">
        <div class="relative">
            <select name="period" onchange="this.form.submit()" class="appearance-none border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @foreach(['today' => 'Today', 'yesterday' => 'Yesterday', 'this_week' => 'This Week', 'last_week' => 'Last Week', 'this_month' => 'This Month', 'last_month' => 'Last Month', 'this_year' => 'This Year'] as $value => $label)
                    <option value="{{ $value }}" {{ $period === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Total Calls</span>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_calls']) }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ number_format($stats['billable_calls']) }} billable</div>
        </div>
    </div>
    
    <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Qualified Leads</span>
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-emerald-600">{{ number_format($stats['qualified_calls']) }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ $stats['qualification_rate'] }}% qualification rate</div>
        </div>
    </div>
    
    <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Total Revenue</span>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-emerald-600">${{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-sm text-gray-500 mt-1">Cost: ${{ number_format($stats['total_cost'], 2) }}</div>
        </div>
    </div>
    
    <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-violet-50 to-violet-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Net Profit</span>
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-blue-600">${{ number_format($stats['total_profit'], 2) }}</div>
            <div class="text-sm text-gray-500 mt-1">Avg: {{ round($stats['avg_duration'] / 60, 1) }} min/call</div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="font-bold text-gray-800 text-lg">Daily Call Volume</h2>
            <p class="text-sm text-gray-500">Calls and revenue by day</p>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            @forelse($dailyStats as $day)
                <div class="flex items-center justify-between p-3 mb-2 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($day->date)->format('M j, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-600">{{ $day->total }} calls</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-emerald-600">${{ number_format($day->revenue ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No data for this period</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="font-bold text-gray-800 text-lg">Performance Metrics</h2>
            <p class="text-sm text-gray-500">Key performance indicators</p>
        </div>
        <div class="p-6 space-y-5">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Billable Calls</div>
                        <div class="text-xs text-gray-500">Calls that generated revenue</div>
                    </div>
                </div>
                <div class="text-xl font-bold text-gray-800">{{ number_format($stats['billable_calls']) }}</div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Total Cost</div>
                        <div class="text-xs text-gray-500">Buyer payouts</div>
                    </div>
                </div>
                <div class="text-xl font-bold text-gray-800">${{ number_format($stats['total_cost'], 2) }}</div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Qualification Rate</div>
                        <div class="text-xs text-gray-500">Qualified / Total calls</div>
                    </div>
                </div>
                <div class="text-xl font-bold text-emerald-600">{{ $stats['qualification_rate'] }}%</div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Avg Duration</div>
                        <div class="text-xs text-gray-500">Average call length</div>
                    </div>
                </div>
                <div class="text-xl font-bold text-gray-800">{{ round($stats['avg_duration'] / 60, 1) }} min</div>
            </div>
        </div>
    </div>
</div>
@endsection
