@extends('admin.layout')
@section('title', 'Call Details')
@section('page-title', 'Call Details')

@section('content')
<div class="mb-6">
    <form method="GET" class="flex gap-2 items-center flex-wrap">
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
            <tr><th class="px-6 py-3">Date</th><th class="px-6 py-3">Caller</th><th class="px-6 py-3">City</th><th class="px-6 py-3">Buyer</th><th class="px-6 py-3">Status</th><th class="px-6 py-3">Duration</th><th class="px-6 py-3">Payout</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($calls as $call)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-xs text-gray-500">{{ $call->created_at->format('M j, g:i A') }}</td>
                    <td class="px-6 py-3 font-mono text-xs">{{ $call->caller_number }}</td>
                    <td class="px-6 py-3">{{ $call->city?->name ?? '—' }}</td>
                    <td class="px-6 py-3">{{ $call->buyer?->company_name ?? '—' }}</td>
                    <td class="px-6 py-3">{!! $call->status_badge !!}</td>
                    <td class="px-6 py-3">{{ $call->duration_formatted }}</td>
                    <td class="px-6 py-3 text-green-600 font-bold">${{ number_format($call->payout, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No calls found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $calls->links() }}</div>
</div>
@endsection
