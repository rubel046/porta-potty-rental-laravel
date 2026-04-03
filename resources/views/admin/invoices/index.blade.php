@extends('admin.layout')
@section('title', 'Invoices')
@section('page-title', 'Invoices Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Invoices</h2>
        <p class="text-sm text-gray-500">Track and manage buyer invoices</p>
    </div>
    <a href="{{ route('admin.invoices.create') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Invoice
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice #..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Buyer</label>
            <select name="buyer_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:bg-gray-50">
                <option value="">All Buyers</option>
                @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" {{ request('buyer_id') == $buyer->id ? 'selected' : '' }}>{{ $buyer->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40" x-data="{ open: false, selected: '{{ request('status') }}' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === 'draft' ? 'Draft' : (selected === 'sent' ? 'Sent' : (selected === 'paid' ? 'Paid' : (selected === 'overdue' ? 'Overdue' : 'All Status')))"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All Status</button>
                    <button type="button" @click="selected = 'draft'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'draft' ? 'bg-green-50 text-green-700 font-medium' : ''">Draft</button>
                    <button type="button" @click="selected = 'sent'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'sent' ? 'bg-green-50 text-green-700 font-medium' : ''">Sent</button>
                    <button type="button" @click="selected = 'paid'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'paid' ? 'bg-green-50 text-green-700 font-medium' : ''">Paid</button>
                    <button type="button" @click="selected = 'overdue'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'overdue' ? 'bg-green-50 text-green-700 font-medium' : ''">Overdue</button>
                </div>
                <input type="hidden" name="status" :value="selected">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'buyer_id', 'status']))
                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
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
                    <th class="px-6 py-4 font-medium">Invoice #</th>
                    <th class="px-6 py-4 font-medium">Buyer</th>
                    <th class="px-6 py-4 font-medium">Period</th>
                    <th class="px-6 py-4 font-medium">Total</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-medium text-gray-900">{{ $invoice->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $invoice->buyer?->company_name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-500">{{ $invoice->period_start->format('M j') }} - {{ $invoice->period_end->format('M j, Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-green-600">${{ number_format($invoice->total_amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php 
                                $statusClasses = [
                                    'draft' => 'bg-gray-100 text-gray-500',
                                    'sent' => 'bg-blue-100 text-blue-700', 
                                    'paid' => 'bg-green-100 text-green-700',
                                    'overdue' => 'bg-red-100 text-red-700'
                                ];
                                $statusDots = [
                                    'draft' => 'bg-gray-400',
                                    'sent' => 'bg-blue-500', 
                                    'paid' => 'bg-green-500',
                                    'overdue' => 'bg-red-500'
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses[$invoice->status] ?? 'bg-gray-100' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$invoice->status] ?? 'bg-gray-400' }}"></span>
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('Are you sure you want to delete this invoice?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <p class="text-gray-500 font-medium">No invoices found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ $invoices->firstItem() }} - {{ $invoices->lastItem() }} of {{ $invoices->total() }}
            </div>
            <nav class="flex items-center gap-1">
                @if($invoices->currentPage() > 1)
                    <a href="{{ $invoices->previousPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                @endif
                
                @foreach($invoices->getUrlRange(max(1, $invoices->currentPage() - 2), min($invoices->lastPage(), $invoices->currentPage() + 2)) as $page => $url)
                    @if($page == $invoices->currentPage())
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($invoices->currentPage() < $invoices->lastPage())
                    <a href="{{ $invoices->nextPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection