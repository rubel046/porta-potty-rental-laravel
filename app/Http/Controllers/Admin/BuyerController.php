<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\City;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = Buyer::withCount('callLogs');

        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%'.$request->search.'%')
                ->orWhere('contact_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $buyers = $query->orderBy('priority')->paginate(20);

        return view('admin.buyers.index', compact('buyers'));
    }

    public function create()
    {
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.buyers.form', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:200',
            'contact_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'backup_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:200',
            'payout_per_call' => 'required|numeric|min:1|max:200',
            'daily_call_cap' => 'required|integer|min:1|max:100',
            'monthly_call_cap' => 'required|integer|min:1|max:5000',
            'timezone' => 'required|string',
            'ring_timeout' => 'required|integer|min:10|max:60',
            'priority' => 'required|integer|min:1|max:10',
            'business_hours_start' => 'nullable|string',
            'business_hours_end' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Format phone number
        $validated['phone'] = $this->formatPhoneE164($validated['phone']);
        if ($validated['backup_phone']) {
            $validated['backup_phone'] = $this->formatPhoneE164($validated['backup_phone']);
        }

        // Business hours
        if ($request->filled('business_hours_start') && $request->filled('business_hours_end')) {
            $validated['business_hours'] = [
                'start' => $validated['business_hours_start'],
                'end' => $validated['business_hours_end'],
            ];
        }
        unset($validated['business_hours_start'], $validated['business_hours_end']);

        Buyer::create($validated);

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer created successfully!');
    }

    public function edit(Buyer $buyer)
    {
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.buyers.form', compact('buyer', 'cities'));
    }

    public function update(Request $request, Buyer $buyer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:200',
            'contact_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'backup_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:200',
            'payout_per_call' => 'required|numeric|min:1|max:200',
            'daily_call_cap' => 'required|integer|min:1|max:100',
            'monthly_call_cap' => 'required|integer|min:1|max:5000',
            'timezone' => 'required|string',
            'ring_timeout' => 'required|integer|min:10|max:60',
            'priority' => 'required|integer|min:1|max:10',
            'business_hours_start' => 'nullable|string',
            'business_hours_end' => 'nullable|string',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['phone'] = $this->formatPhoneE164($validated['phone']);
        if ($validated['backup_phone']) {
            $validated['backup_phone'] = $this->formatPhoneE164($validated['backup_phone']);
        }

        if ($request->filled('business_hours_start') && $request->filled('business_hours_end')) {
            $validated['business_hours'] = [
                'start' => $validated['business_hours_start'],
                'end' => $validated['business_hours_end'],
            ];
        }
        unset($validated['business_hours_start'], $validated['business_hours_end']);

        $buyer->update($validated);

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer updated!');
    }

    public function destroy(Buyer $buyer)
    {
        $buyer->delete();

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer deleted!');
    }

    protected function formatPhoneE164(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) === 10) {
            return '+1'.$digits;
        }
        if (strlen($digits) === 11 && $digits[0] === '1') {
            return '+'.$digits;
        }

        return $phone;
    }
}
