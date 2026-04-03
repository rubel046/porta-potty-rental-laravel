<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PhoneNumber;
use App\Services\SignalWireService;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    public function index(Request $request)
    {
        $query = PhoneNumber::with(['city.state', 'buyer']);

        if ($request->filled('search')) {
            $query->where('number', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $phoneNumbers = $query->orderByDesc('created_at')->paginate(20);
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.phone-numbers.index', compact('phoneNumbers', 'cities'));
    }

    public function create()
    {
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.phone-numbers.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:phone_numbers,number',
            'friendly_name' => 'nullable|string|max:255',
            'city_id' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $number = preg_replace('/[^0-9]/', '', $request->number);
        $areaCode = strlen($number) >= 10 ? substr($number, 0, 3) : null;

        PhoneNumber::create([
            'number' => $request->number,
            'friendly_name' => $request->filled('friendly_name') ? $request->friendly_name : $request->number,
            'area_code' => $areaCode,
            'city_id' => $request->city_id ?: null,
            'is_active' => $request->boolean('is_active', true),
            'provider' => 'manual',
        ]);

        return redirect()->route('admin.phone-numbers.index')
            ->with('success', 'Phone number added successfully!');
    }

    public function show(PhoneNumber $phoneNumber)
    {
        $phoneNumber->load(['city.state', 'buyer', 'callLogs']);

        return view('admin.phone-numbers.show', compact('phoneNumber'));
    }

    public function edit(PhoneNumber $phoneNumber)
    {
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.phone-numbers.edit', compact('phoneNumber', 'cities'));
    }

    public function update(Request $request, PhoneNumber $phoneNumber)
    {
        $request->validate([
            'number' => 'required|string|unique:phone_numbers,number,'.$phoneNumber->id,
            'friendly_name' => 'nullable|string|max:255',
            'city_id' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $phoneNumber->update([
            'number' => $phoneNumber->number,
            'friendly_name' => $request->filled('friendly_name') ? $request->friendly_name : $phoneNumber->number,
            'area_code' => $phoneNumber->area_code,
            'city_id' => $request->city_id ?: null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.phone-numbers.index')
            ->with('success', 'Phone number updated successfully!');
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

    public function destroy(PhoneNumber $phoneNumber)
    {
        $phoneNumber->delete();

        return redirect()->route('admin.phone-numbers.index')
            ->with('success', 'Phone number deleted successfully!');
    }
}
