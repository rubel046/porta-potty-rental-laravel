<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\CallLog;
use App\Models\City;
use Illuminate\Http\Request;

class CallLogController extends Controller
{
    public function index(Request $request)
    {
        $query = CallLog::with(['city', 'buyer', 'phoneNumber'])
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'qualified') {
                $query->where('is_qualified', true);
            } elseif ($request->status === 'unqualified') {
                $query->where('is_qualified', false);
            } elseif (in_array($request->status, ['callback', 'voicemail'])) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('disposition')) {
            $query->where('buyer_disposition', $request->disposition);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('buyer_id')) {
            $query->where('buyer_id', $request->buyer_id);
        }

        $calls = $query->paginate(50);
        $cities = City::active()->orderBy('name')->get();
        $buyers = Buyer::active()->orderBy('company_name')->get();

        return view('admin.calls.index', compact('calls', 'cities', 'buyers'));
    }

    public function edit(CallLog $callLog)
    {
        $callLog->load(['city', 'buyer', 'phoneNumber']);

        return view('admin.calls.edit', compact('callLog'));
    }

    public function update(Request $request, CallLog $callLog)
    {
        $validated = $request->validate([
            'buyer_disposition' => 'nullable|string|in:booked,not_interested,price_shopper,wrong_area,callback,voicemail',
            'buyer_notes' => 'nullable|string|max:500',
        ]);

        $callLog->update($validated);

        return redirect()->route('admin.calls.edit', $callLog)
            ->with('success', 'Call disposition updated.');
    }
}
