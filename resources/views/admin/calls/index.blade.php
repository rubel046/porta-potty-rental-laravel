@extends('admin.layout')
@section('title', 'All Calls')
@section('page-title', 'All Calls')

@section('content')
<div class="mb-4 flex flex-wrap justify-between items-center gap-4">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
            <option value="unqualified" {{ request('status') == 'unqualified' ? 'selected' : '' }}>Unqualified</option>
            <option value="callback" {{ request('status') == 'callback' ? 'selected' : '' }}>Callback</option>
            <option value="voicemail" {{ request('status') == 'voicemail' ? 'selected' : '' }}>Voicemail</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Caller</th>
                    <th class="px-6 py-3">City</th>
                    <th class="px-6 py-3">Buyer</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Duration</th>
                    <th class="px-6 py-3">Payout</th>
                </tr>
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
                        <td class="px-6 py-3 font-bold text-green-600">${{ number_format($call->payout, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No calls found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $calls->links() }}
    </div>
</div>
@endsection
