@extends('admin.layout')
@section('title', "Invoice {$invoice->invoice_number}")
@section('page-title', "Invoice {$invoice->invoice_number}")

@section('content')

    <div class="max-w-4xl">
        {{-- Invoice Header --}}
        <div class="card p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                    <p class="text-gray-500 mt-1">
                        {{ $invoice->buyer->company_name }}
                    </p>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-600',
                            'sent' => 'bg-blue-100 text-blue-700',
                            'paid' => 'bg-green-100 text-green-700',
                            'overdue' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="{{ $statusColors[$invoice->status] ?? 'bg-gray-100' }} text-sm px-3 py-1 rounded-full font-medium capitalize">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-sm">
                <div>
                    <span class="text-gray-500 block">Period</span>
                    <span class="font-medium">
                        {{ $invoice->period_start->format('M d') }} — {{ $invoice->period_end->format('M d, Y') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 block">Due Date</span>
                    <span class="font-medium">{{ $invoice->due_date?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Billable Calls</span>
                    <span class="font-medium">{{ $invoice->billable_calls }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Total Amount</span>
                    <span class="text-2xl font-bold text-green-600">${{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 mt-6 pt-4 border-t">
                @if($invoice->status === 'draft')
                    <form method="POST" action="{{ route('admin.invoices.send', $invoice) }}">
                        @csrf
                        <button class="btn-primary">📧 Mark as Sent</button>
                    </form>
                @endif

                @if($invoice->status !== 'paid')
                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}">
                        @csrf
                        <button class="btn-success">✅ Mark as Paid</button>
                    </form>
                @endif

                <a href="{{ route('admin.invoices.index') }}" class="btn-secondary">← Back to Invoices</a>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="card overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="font-bold text-gray-700">Call Details</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="p-3">#</th>
                    <th class="p-3">Date/Time</th>
                    <th class="p-3">Caller</th>
                    <th class="p-3">Duration</th>
                    <th class="p-3 text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $i => $item)
                    <tr class="border-b border-gray-50">
                        <td class="p-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="p-3">
                            {{ $item->callLog?->created_at?->format('M d, Y h:i A') ?? '—' }}
                        </td>
                        <td class="p-3 font-mono text-xs">
                            {{ $item->callLog?->caller_number ?? '—' }}
                        </td>
                        <td class="p-3">
                            {{ $item->callLog?->duration_formatted ?? '—' }}
                        </td>
                        <td class="p-3 text-right font-bold">${{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="bg-gray-50 font-bold">
                    <td colspan="4" class="p-3 text-right">Total:</td>
                    <td class="p-3 text-right text-green-600 text-lg">
                        ${{ number_format($invoice->total_amount, 2) }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
