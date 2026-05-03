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
            'source' => 'nullable|string|max:100',
            'website' => 'nullable|max:0', // honeypot — bots fill visible-hidden fields
        ]);

        // Silently accept and drop honeypot hits so scrapers can't detect the filter
        if (! empty($request->input('website'))) {
            return back()->with('success', 'thanks');
        }

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'zip' => $validated['zip'] ?? null,
            'service_type' => $validated['service_type'] ?? null,
            'status' => Lead::STATUS_PENDING,
            'source' => $validated['source'] ?? 'web-form',
        ]);

        Log::info('New lead captured', [
            'lead_id' => $lead->id,
            'source' => $lead->source,
            'phone' => $lead->phone,
        ]);

        return back()
            ->with('success', $lead->phone)
            ->with('lead_id', $lead->id);
    }
}
