@extends('admin.layout')
@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')

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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-bold text-gray-800">Revenue by Buyer</h2></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase"><tr><th class="px-6 py-3">Buyer</th><th class="px-6 py-3">Calls</th><th class="px-6 py-3">Revenue</th><th class="px-6 py-3">Cost</th><th class="px-6 py-3">Profit</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($revenueByBuyer as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $row->buyer?->company_name ?? 'Unknown' }}</td>
                    <td class="px-6 py-3">{{ $row->call_count }}</td>
                    <td class="px-6 py-3 text-green-600 font-bold">${{ number_format($row->total_revenue, 2) }}</td>
                    <td class="px-6 py-3 text-red-500">${{ number_format($row->total_cost ?? 0, 2) }}</td>
                    <td class="px-6 py-3 text-blue-600">${{ number_format($row->profit ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-bold text-gray-800">Revenue by City (Top 20)</h2></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase"><tr><th class="px-6 py-3">City</th><th class="px-6 py-3">Calls</th><th class="px-6 py-3">Revenue</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($revenueByCity as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $row->city?->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-3">{{ $row->call_count }}</td>
                    <td class="px-6 py-3 text-green-600 font-bold">${{ number_format($row->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400">No data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
