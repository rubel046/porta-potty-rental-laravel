@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Quick Actions --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.cities.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 hover:shadow-xl hover:shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="font-bold text-white text-lg">Add City</div>
                <div class="text-emerald-100 text-sm">Create new location</div>
            </div>
        </a>
        <a href="{{ route('admin.phone-numbers.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div class="font-bold text-white text-lg">Add Phone</div>
                <div class="text-blue-100 text-sm">New tracking number</div>
            </div>
        </a>
        <a href="{{ route('admin.buyers.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-5 hover:shadow-xl hover:shadow-violet-500/20 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="font-bold text-white text-lg">Add Buyer</div>
                <div class="text-violet-100 text-sm">New lead buyer</div>
            </div>
        </a>
        <a href="{{ route('admin.blog-posts.create') }}" class="group relative overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-5 hover:shadow-xl hover:shadow-orange-500/20 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div class="font-bold text-white text-lg">New Post</div>
                <div class="text-orange-100 text-sm">Write blog article</div>
            </div>
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Today's Calls</span>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($todayStats['total_calls']) }}</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                        {{ $todayStats['qualified_calls'] }} qualified
                    </span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Today's Revenue</span>
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-emerald-600">${{ number_format($todayStats['revenue'], 2) }}</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm text-gray-500">Profit:</span>
                    <span class="text-sm font-medium text-gray-700">${{ number_format($todayStats['profit'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">This Month</span>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($monthStats['total_calls']) }}</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm font-medium text-emerald-600">${{ number_format($monthStats['revenue'], 2) }}</span>
                    <span class="text-sm text-gray-500">revenue</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-violet-50 to-violet-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Qualification Rate</span>
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-blue-600">{{ $todayStats['qualification_rate'] }}%</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm text-gray-500">Avg:</span>
                    <span class="text-sm font-medium text-gray-700">{{ round($todayStats['avg_duration'] / 60, 1) }} min</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Resource Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.cities.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($resourceStats['active_cities']) }}</div>
                    <div class="text-sm text-gray-500">Active Cities</div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.service-pages.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-purple-200 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($resourceStats['published_pages']) }}</div>
                    <div class="text-sm text-gray-500">Published Pages</div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.phone-numbers.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-emerald-200 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($resourceStats['active_numbers']) }}</div>
                    <div class="text-sm text-gray-500">Active Numbers</div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.buyers.index') }}" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-600">{{ number_format($resourceStats['active_buyers']) }}</div>
                    <div class="text-sm text-gray-500">Active Buyers</div>
                </div>
            </div>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Recent Calls --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">Recent Calls</h2>
                    <p class="text-sm text-gray-500">Latest incoming calls</p>
                </div>
                <a href="{{ route('admin.calls.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-4 font-medium">Caller</th>
                            <th class="px-6 py-4 font-medium">City</th>
                            <th class="px-6 py-4 font-medium">Buyer</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentCalls as $call)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span class="font-mono text-xs text-gray-700">{{ $call->caller_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">{{ $call->city?->name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-500">{{ $call->buyer?->company_name ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">{!! $call->status_badge !!}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 text-gray-600 text-xs font-medium">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $call->duration_formatted }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">No calls yet</p>
                                        <p class="text-sm text-gray-400 mt-1">Incoming calls will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Cities --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="font-bold text-gray-800 text-lg">Top Cities</h2>
                <p class="text-sm text-gray-500">This month by revenue</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse($topCities as $index => $cityStat)
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold @if($index === 0) bg-amber-100 text-amber-700 @elseif($index === 1) bg-gray-100 text-gray-600 @else bg-gray-50 text-gray-500 @endif">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-800">{{ $cityStat->city?->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $cityStat->call_count }} calls</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-emerald-600">${{ number_format($cityStat->total_revenue, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <p class="text-gray-400 text-sm">No data yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
