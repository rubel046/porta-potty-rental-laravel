@extends('admin.layout')
@section('title', 'City Report')
@section('page-title', 'City Report')

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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
            <tr><th class="px-6 py-3">City</th><th class="px-6 py-3">Total Calls</th><th class="px-6 py-3">Qualified</th><th class="px-6 py-3">Revenue</th><th class="px-6 py-3">Cost</th><th class="px-6 py-3">Profit</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cityStats as $stat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $stat->city?->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-3">{{ $stat->total_calls }}</td>
                    <td class="px-6 py-3 text-green-600">{{ $stat->qualified_calls }}</td>
                    <td class="px-6 py-3 text-green-600 font-bold">${{ number_format($stat->revenue, 2) }}</td>
                    <td class="px-6 py-3 text-red-500">${{ number_format($stat->cost ?? 0, 2) }}</td>
                    <td class="px-6 py-3 text-blue-600">${{ number_format($stat->profit ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No data</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $cityStats->links() }}</div>
</div>
@endsection
