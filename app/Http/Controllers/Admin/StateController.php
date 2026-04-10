<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateStateContentJob;
use App\Models\Domain;
use App\Models\DomainState;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class StateController extends Controller
{
    public function index(): Response
    {
        $domain = Domain::current();

        if ($domain) {
            $states = State::select('states.*')
                ->join('domain_states', 'states.id', '=', 'domain_states.state_id')
                ->where('domain_states.domain_id', $domain->id)
                ->withCount(['cities', 'activeCities'])
                ->orderByDesc('domain_states.status')
                ->orderBy('name')
                ->get()
                ->map(function ($state) use ($domain) {
                    $domainState = DomainState::where('domain_id', $domain->id)
                        ->where('state_id', $state->id)
                        ->first();
                    $state->domain_status = $domainState?->status ?? false;

                    return $state;
                });

            return response(view('admin.states.index', compact('states', 'domain')));
        }

        $states = State::withCount(['cities', 'activeCities'])
            ->orderBy('name')
            ->get();

        return response(view('admin.states.index', compact('states', 'domain')));
    }

    public function edit(State $state): Response
    {
        $domain = Domain::current();
        $state->loadCount(['cities', 'activeCities']);

        if ($domain) {
            $domainState = DomainState::where('domain_id', $domain->id)
                ->where('state_id', $state->id)
                ->first();
            $state->domain_status = $domainState?->status ?? false;
        }

        return response(view('admin.states.edit', compact('state', 'domain')));
    }

    public function update(State $state): RedirectResponse
    {
        $validated = request()->validate([
            'h1_title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'content' => 'nullable|string',
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

    public function toggleStatus(State $state): RedirectResponse
    {
        $domain = Domain::current();

        if (! $domain) {
            return redirect()->back()->with('error', 'No domain selected');
        }

        $domainState = DomainState::where('domain_id', $domain->id)
            ->where('state_id', $state->id)
            ->first();

        if ($domainState) {
            $domainState->update(['status' => ! $domainState->status]);
            $statusText = $domainState->status ? 'activated' : 'deactivated';
        } else {
            DomainState::create([
                'domain_id' => $domain->id,
                'state_id' => $state->id,
                'status' => true,
            ]);
            $statusText = 'activated';
        }

        return redirect()->back()->with('success', "State '{$state->name}' {$statusText}!");
    }
}
