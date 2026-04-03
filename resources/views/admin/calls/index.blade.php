@extends('admin.layout')
@section('title', 'All Calls')
@section('page-title', 'Call Logs')

@section('content')
<div class="mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Call Logs</h2>
    <p class="text-sm text-gray-500">Track and analyze incoming calls</p>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" 
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" 
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
        <div class="w-40" x-data="{ open: false, selected: '{{ request('status') }}' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === 'qualified' ? 'Qualified' : (selected === 'unqualified' ? 'Unqualified' : (selected === 'callback' ? 'Callback' : (selected === 'voicemail' ? 'Voicemail' : 'All Status')))"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All Status</button>
                    <button type="button" @click="selected = 'qualified'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'qualified' ? 'bg-green-50 text-green-700 font-medium' : ''">Qualified</button>
                    <button type="button" @click="selected = 'unqualified'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'unqualified' ? 'bg-green-50 text-green-700 font-medium' : ''">Unqualified</button>
                    <button type="button" @click="selected = 'callback'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'callback' ? 'bg-green-50 text-green-700 font-medium' : ''">Callback</button>
                    <button type="button" @click="selected = 'voicemail'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'voicemail' ? 'bg-green-50 text-green-700 font-medium' : ''">Voicemail</button>
                </div>
                <input type="hidden" name="status" :value="selected">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['from', 'to', 'status']))
                <a href="{{ route('admin.calls.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">Date</th>
                    <th class="px-6 py-4 font-medium">Caller</th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">Buyer</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Duration</th>
                    <th class="px-6 py-4 font-medium">Payout</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($calls as $call)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-500">{{ $call->created_at->format('M j, g:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-gray-900">{{ $call->caller_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $call->city?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-500">{{ $call->buyer?->company_name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {!! $call->status_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-500">{{ $call->duration_formatted }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-green-600">${{ number_format($call->payout, 2) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <p class="text-gray-500 font-medium">No calls found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($calls->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ $calls->firstItem() }} - {{ $calls->lastItem() }} of {{ $calls->total() }}
            </div>
            <nav class="flex items-center gap-1">
                @if($calls->currentPage() > 1)
                    <a href="{{ $calls->previousPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                @endif
                
                @foreach($calls->getUrlRange(max(1, $calls->currentPage() - 2), min($calls->lastPage(), $calls->currentPage() + 2)) as $page => $url)
                    @if($page == $calls->currentPage())
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($calls->currentPage() < $calls->lastPage())
                    <a href="{{ $calls->nextPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection