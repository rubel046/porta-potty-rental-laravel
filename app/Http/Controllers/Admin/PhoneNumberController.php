<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PhoneNumber;
use App\Services\SignalWireService;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    public function index()
    {
        $numbers = PhoneNumber::with(['city.state', 'buyer'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.phone-numbers.index', compact('numbers'));
    }

    public function create()
    {
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.phone-numbers.create', compact('cities'));
    }

    public function purchase(Request $request, SignalWireService $signalWire)
    {
        $request->validate([
            'area_code' => 'required|string|size:3',
            'city_id' => 'nullable|exists:cities,id',
        ]);

        // Search available numbers
        $available = $signalWire->searchAvailableNumbers($request->area_code, 5);

        if (empty($available)) {
            return redirect()->back()
                ->with('error', "No numbers available for area code {$request->area_code}");
        }

        // Purchase first available
        $phoneNumber = $signalWire->purchaseNumber(
            $available[0]['phone_number'],
            $request->city_id
        );

        if (! $phoneNumber) {
            return redirect()->back()
                ->with('error', 'Failed to purchase number. Check SignalWire credentials.');
        }

        return redirect()->route('admin.phone-numbers.index')
            ->with('success', "Number {$phoneNumber->friendly_name} purchased!");
    }
}
