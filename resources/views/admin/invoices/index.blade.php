@extends('admin.layout')
@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="buyer_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Buyers</option>
            @foreach($buyers as $buyer)
                <option value="{{ $buyer->id }}" {{ request('buyer_id') == $buyer->id ? 'selected' : '' }}>{{ $buyer->company_name }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
    <a href="{{ route('admin.invoices.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">+ Create Invoice</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
            <tr><th class="px-6 py-3">Invoice #</th><th class="px-6 py-3">Buyer</th><th class="px-6 py-3">Period</th><th class="px-6 py-3">Total</th><th class="px-6 py-3">Status</th><th class="px-6 py-3">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono text-xs">{{ $invoice->invoice_number }}</td>
                    <td class="px-6 py-3">{{ $invoice->buyer?->company_name ?? '—' }}</td>
                    <td class="px-6 py-3 text-xs text-gray-500">{{ $invoice->period_start->format('M j') }} - {{ $invoice->period_end->format('M j, Y') }}</td>
                    <td class="px-6 py-3 font-bold">${{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="px-6 py-3">
                        @php $statusColors = ['draft' => 'bg-gray-100 text-gray-500', 'sent' => 'bg-blue-100 text-blue-700', 'paid' => 'bg-green-100 text-green-700', 'overdue' => 'bg-red-100 text-red-700']; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100' }} capitalize">{{ $invoice->status }}</span>
                    </td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 text-xs">View</a>
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No invoices found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $invoices->links() }}</div>
</div>
@endsection
