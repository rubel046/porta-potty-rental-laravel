<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateStateContentJob;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class StateController extends Controller
{
    public function index(): Response
    {
        $states = State::withCount(['cities', 'activeCities'])
            ->orderBy('name')
            ->get();

        return response(view('admin.states.index', compact('states')));
    }

    public function edit(State $state): Response
    {
        $state->loadCount(['cities', 'activeCities']);

        return response(view('admin.states.edit', compact('state')));
    }

    public function update(State $state): RedirectResponse
    {
        $validated = request()->validate([
            'h1_title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $state->update($validated);

        return redirect()->route('admin.states.index')
            ->with('success', "State '{$state->name}' updated!");
    }

    public function generateContent(State $state): RedirectResponse
    {
        $cacheKey = "state_content_generation_{$state->id}";

        if (Cache::get("{$cacheKey}_status") === 'processing') {
            return redirect()->back()->with('info', 'Content generation is already in progress!');
        }

        GenerateStateContentJob::dispatch($state);

        return redirect()->back()->with('success', 'Content generation started in background! Refresh the page to see progress.');
    }

    public function generationProgress(State $state): JsonResponse
    {
        $cacheKey = "state_content_generation_{$state->id}";

        return response()->json([
            'status' => Cache::get("{$cacheKey}_status", 'idle'),
            'progress' => Cache::get("{$cacheKey}_progress", 0),
            'started_at' => Cache::get("{$cacheKey}_started_at"),
            'error' => Cache::get("{$cacheKey}_error"),
        ]);
    }
}
