@extends('admin.layout')
@section('title', 'Edit Call #' . $callLog->id)
@section('page-title', 'Edit Call #' . $callLog->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.calls.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to All Calls</a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Call Details --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Call Information</h3>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Caller</dt><dd class="font-mono text-gray-900">{{ $callLog->caller_number }}</dd></div>
                <div><dt class="text-gray-500">Called</dt><dd class="font-mono text-gray-900">{{ $callLog->called_number }}</dd></div>
                <div><dt class="text-gray-500">Date</dt><dd class="text-gray-900">{{ $callLog->created_at->format('M j, Y g:i A') }}</dd></div>
                <div><dt class="text-gray-500">Duration</dt><dd class="text-gray-900">{{ $callLog->duration_formatted }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>{!! $callLog->status_badge !!}</dd></div>
                <div><dt class="text-gray-500">City</dt><dd class="text-gray-900">{{ $callLog->city?->name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Buyer</dt><dd class="text-gray-900">{{ $callLog->buyer?->company_name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Payout</dt><dd class="font-bold text-green-600">${{ number_format($callLog->payout, 2) }}</dd></div>
                <div><dt class="text-gray-500">Qualified</dt><dd>@if($callLog->is_qualified)<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Yes</span>@else<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">No</span>@endif</dd></div>
                <div><dt class="text-gray-500">IVR Passed</dt><dd>@if($callLog->ivr_passed)<span class="text-green-600">✓</span>@else<span class="text-red-400">✗</span>@endif</dd></div>
                <div class="col-span-2"><dt class="text-gray-500">Traffic Source</dt><dd class="text-gray-900">{{ $callLog->traffic_source ?? '—' }}</dd></div>
                @if($callLog->recording_url)
                <div class="col-span-2">
                    <dt class="text-gray-500 mb-2">Recording</dt>
                    <dd><a href="{{ $callLog->recording_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline text-sm">Listen to recording &rarr;</a></dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Buyer Disposition Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Buyer Disposition</h3>
            <form method="POST" action="{{ route('admin.calls.update', $callLog) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Outcome</label>
                    <select name="buyer_disposition" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">— Not set —</option>
                        <option value="booked" @selected($callLog->buyer_disposition === 'booked')>Booked — Caller booked a rental</option>
                        <option value="callback" @selected($callLog->buyer_disposition === 'callback')>Callback — Caller requested callback</option>
                        <option value="price_shopper" @selected($callLog->buyer_disposition === 'price_shopper')>Price Shopper — Just checking prices</option>
                        <option value="wrong_area" @selected($callLog->buyer_disposition === 'wrong_area')>Wrong Area — Outside service area</option>
                        <option value="not_interested" @selected($callLog->buyer_disposition === 'not_interested')>Not Interested — Not interested</option>
                        <option value="voicemail" @selected($callLog->buyer_disposition === 'voicemail')>Voicemail — Went to voicemail</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="buyer_notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Buyer notes about this call...">{{ old('buyer_notes', $callLog->buyer_notes) }}</textarea>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Save Disposition
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Disposition Stats</h3>
            @php
                $total = CallLog::count();
                $booked = CallLog::where('buyer_disposition', 'booked')->count();
            @endphp
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-600">Total Calls</span><span class="font-semibold">{{ $total }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Booked</span><span class="font-semibold text-green-600">{{ $booked }}</span></div>
                <div class="flex justify-between"><span class="text-gray-600">Conversion</span><span class="font-semibold">{{ $total > 0 ? round(($booked / $total) * 100) : 0 }}%</span></div>
            </div>
        </div>
    </div>
</div>
@endsection