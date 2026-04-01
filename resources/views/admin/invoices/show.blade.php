@extends('admin.layout')
@section('title', 'Invoice Details')
@section('page-title', 'Invoice: ' . $invoice->invoice_number)

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                    <p class="text-gray-500 text-sm">{{ $invoice->buyer?->company_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-green-600">${{ number_format($invoice->total_amount, 2) }}</p>
                    @php $statusColors = ['draft' => 'bg-gray-100 text-gray-500', 'sent' => 'bg-blue-100 text-blue-700', 'paid' => 'bg-green-100 text-green-700', 'overdue' => 'bg-red-100 text-red-700']; @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100' }} capitalize">{{ $invoice->status }}</span>
                </div>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div><dt class="text-gray-500">Period</dt><dd>{{ $invoice->period_start->format('M j, Y') }} — {{ $invoice->period_end->format('M j, Y') }}</dd></div>
                <div><dt class="text-gray-500">Due Date</dt><dd>{{ $invoice->due_date?->format('M j, Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Total Calls</dt><dd>{{ number_format($invoice->total_calls) }}</dd></div>
                <div><dt class="text-gray-500">Qualified Calls</dt><dd>{{ number_format($invoice->qualified_calls) }}</dd></div>
                <div><dt class="text-gray-500">Billable Calls</dt><dd>{{ number_format($invoice->billable_calls) }}</dd></div>
                <div><dt class="text-gray-500">Subtotal</dt><dd>${{ number_format($invoice->subtotal, 2) }}</dd></div>
                <div><dt class="text-gray-500">Adjustments</dt><dd>${{ number_format($invoice->adjustments, 2) }}</dd></div>
            </dl>

            @if($invoice->notes)
                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600 mb-6">
                    <strong>Notes:</strong> {{ $invoice->notes }}
                </div>
            @endif

            <div class="flex gap-2">
                @if($invoice->status === 'draft')
                    <form method="POST" action="{{ route('admin.invoices.send', $invoice) }}">@csrf<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Mark as Sent</button></form>
                @endif
                @if($invoice->status !== 'paid')
                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}">@csrf<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Mark as Paid</button></form>
                @endif
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Edit</a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Buyer</h3>
            @if($invoice->buyer)
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-gray-500">Company</dt><dd class="font-medium">{{ $invoice->buyer->company_name }}</dd></div>
                    <div><dt class="text-gray-500">Contact</dt><dd>{{ $invoice->buyer->contact_name }}</dd></div>
                    <div><dt class="text-gray-500">Phone</dt><dd>{{ $invoice->buyer->phone }}</dd></div>
                    <div><dt class="text-gray-500">Email</dt><dd>{{ $invoice->buyer->email ?? '—' }}</dd></div>
                </dl>
            @endif
        </div>
    </div>
</div>
@endsection
