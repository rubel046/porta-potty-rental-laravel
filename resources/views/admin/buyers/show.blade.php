@extends('admin.layout')
@section('title', $buyer->company_name)
@section('page-title', $buyer->company_name)

@section('content')
<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">Buyer Details</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Company</dt><dd class="font-medium">{{ $buyer->company_name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Contact</dt><dd>{{ $buyer->contact_name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd>{{ $buyer->phone }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd>{{ $buyer->email ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Payout/Call</dt><dd class="text-green-600 font-bold">${{ number_format($buyer->payout_per_call, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Total Billed</dt><dd>${{ number_format($buyer->total_billed, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Total Calls</dt><dd>{{ number_format($buyer->total_calls) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">This Month Revenue</dt><dd class="text-green-600">${{ number_format($buyer->this_month_revenue, 2) }}</dd></div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Status</dt>
                <dd>
                    @if($buyer->is_active)
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </dd>
            </div>
        </dl>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('admin.buyers.edit', $buyer) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-4">Recent Calls</h2>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="pb-2">Date</th><th class="pb-2">Caller</th><th class="pb-2">Duration</th><th class="pb-2">Payout</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($buyer->callLogs as $call)
                    <tr>
                        <td class="py-2 text-xs text-gray-500">{{ $call->created_at->format('M j') }}</td>
                        <td class="py-2 font-mono text-xs">{{ $call->caller_number }}</td>
                        <td class="py-2">{{ $call->duration_formatted }}</td>
                        <td class="py-2 text-green-600">${{ number_format($call->payout, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-400">No calls</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
