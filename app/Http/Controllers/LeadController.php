<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'zip' => 'nullable|string|max:10',
            'service_type' => 'nullable|string|max:50',
        ]);

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'zip' => $validated['zip'] ?? null,
            'service_type' => $validated['service_type'] ?? null,
            'status' => Lead::STATUS_PENDING,
            'source' => 'homepage',
        ]);

        Log::info('New lead created from homepage', ['lead_id' => $lead->id, 'phone' => $lead->phone]);

        return back()
            ->with('success', $lead->phone)
            ->with('lead_id', $lead->id);
    }
}
