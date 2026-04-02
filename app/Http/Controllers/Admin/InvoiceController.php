<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\CallLog;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('buyer');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('buyer_id')) {
            $query->where('buyer_id', $request->buyer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(20);
        $buyers = Buyer::active()->orderBy('company_name')->get();

        return view('admin.invoices.index', compact('invoices', 'buyers'));
    }

    public function create()
    {
        $buyers = Buyer::active()->orderBy('company_name')->get();

        return view('admin.invoices.create', compact('buyers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $buyer = Buyer::findOrFail($validated['buyer_id']);

        // Get billable calls for this period
        $calls = CallLog::where('buyer_id', $buyer->id)
            ->where('is_billable', true)
            ->whereBetween('created_at', [
                Carbon::parse($validated['period_start'])->startOfDay(),
                Carbon::parse($validated['period_end'])->endOfDay(),
            ])
            ->get();

        // Generate invoice number
        $lastInvoice = Invoice::latest('id')->first();
        $nextNumber = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -3)) + 1 : 1;
        $invoiceNumber = 'INV-'.date('Y').'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $totalAmount = $calls->sum('payout');

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'buyer_id' => $buyer->id,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'total_calls' => $calls->count(),
            'qualified_calls' => $calls->where('is_qualified', true)->count(),
            'billable_calls' => $calls->count(),
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount,
            'status' => 'draft',
            'due_date' => now()->addDays(7),
        ]);

        // Create line items
        foreach ($calls as $call) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'call_log_id' => $call->id,
                'description' => "Call from {$call->caller_number} - {$call->duration_formatted} - {$call->created_at->format('M d, Y h:i A')}",
                'amount' => $call->payout,
            ]);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', "Invoice {$invoiceNumber} created for \${$totalAmount} ({$calls->count()} calls)");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['buyer', 'items.callLog']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);

        return redirect()->back()->with('success', "Invoice {$invoice->invoice_number} marked as paid!");
    }

    public function send(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);

        // TODO: Send email to buyer
        return redirect()->back()->with('success', "Invoice {$invoice->invoice_number} marked as sent!");
    }
}
