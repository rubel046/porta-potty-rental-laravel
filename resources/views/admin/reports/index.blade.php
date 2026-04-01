@extends('admin.layout')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<div class="mb-6">
    <form method="GET" class="flex gap-2 items-center">
        <label class="text-sm text-gray-600">Period:</label>
        <select name="period" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            @foreach(['today' => 'Today', 'yesterday' => 'Yesterday', 'this_week' => 'This Week', 'last_week' => 'Last Week', 'this_month' => 'This Month', 'last_month' => 'Last Month', 'this_year' => 'This Year'] as $value => $label)
                <option value="{{ $value }}" {{ $period === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-sm text-gray-500 mb-1">Total Calls</div>
        <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_calls']) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-sm text-gray-500 mb-1">Qualified</div>
        <div class="text-3xl font-bold text-green-600">{{ number_format($stats['qualified_calls']) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-sm text-gray-500 mb-1">Revenue</div>
        <div class="text-3xl font-bold text-green-600">${{ number_format($stats['total_revenue'], 2) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-sm text-gray-500 mb-1">Profit</div>
        <div class="text-3xl font-bold text-blue-600">${{ number_format($stats['total_profit'], 2) }}</div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">Daily Call Volume</h2>
        <div class="space-y-2 max-h-64 overflow-y-auto">
            @forelse($dailyStats as $day)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg text-sm">
                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('M j, Y') }}</span>
                    <div class="flex gap-4 text-right">
                        <span class="text-gray-500">{{ $day->total }} calls</span>
                        <span class="text-green-600 font-medium">${{ number_format($day->revenue ?? 0, 2) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 text-sm py-4">No data for this period</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">Quick Stats</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Billable Calls</dt><dd class="font-medium">{{ number_format($stats['billable_calls']) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Total Cost</dt><dd class="font-medium">${{ number_format($stats['total_cost'], 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Qualification Rate</dt><dd class="font-medium">{{ $stats['qualification_rate'] }}%</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Avg Duration</dt><dd class="font-medium">{{ round($stats['avg_duration'] / 60, 1) }} min</dd></div>
        </dl>
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-4">
    <span class="text-gray-500 text-sm">Additional reports coming soon</span>
</div>
@endsection
