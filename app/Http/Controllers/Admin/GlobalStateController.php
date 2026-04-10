<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateStateContentJob;
use App\Models\Domain;
use App\Models\DomainState;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class GlobalStateController extends Controller
{
    public function index(Request $request): Response
    {
        $query = State::query()->withCount('cities');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }

        $states = $query->orderBy('name')->paginate(30);

        return response(view('admin.global-states.index', compact('states')));
    }

    public function create(): Response
    {
        return response(view('admin.global-states.create'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2|unique:states,code',
            'slug' => 'required|string|max:255|unique:states,slug',
            'timezone' => 'nullable|string|max:50',
        ]);

        $state = State::create($validated);

        $domains = Domain::where('is_active', true)->get();
        foreach ($domains as $domain) {
            DomainState::firstOrCreate(
                ['domain_id' => $domain->id, 'state_id' => $state->id],
                ['status' => false]
            );
        }

        return redirect()->route('admin.global.states.index')
            ->with('success', "State '{$state->name}' created!");
    }

    public function show(State $state): Response
    {
        $state->loadCount(['cities', 'activeCities']);

        return response(view('admin.global-states.show', compact('state')));
    }

    public function edit(State $state): Response
    {
        $state->loadCount(['cities', 'activeCities']);

        return response(view('admin.global-states.edit', compact('state')));
    }

    public function update(Request $request, State $state): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2|unique:states,code,'.$state->id,
            'slug' => 'required|string|max:255|unique:states,slug,'.$state->id,
            'timezone' => 'nullable|string|max:50',
            'h1_title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
        ]);

        $state->update($validated);

        return redirect()->route('admin.global.states.index')
            ->with('success', "State '{$state->name}' updated!");
    }

    public function destroy(State $state): RedirectResponse
    {
        $name = $state->name;

        DomainState::where('state_id', $state->id)->delete();

        $state->delete();

        return redirect()->route('admin.global.states.index')
            ->with('success', "State '{$name}' deleted!");
    }

    public function generateContent(State $state): RedirectResponse
    {
        $cacheKey = "state_content_generation_{$state->id}";

        if (Cache::get("{$cacheKey}_status") === 'processing') {
            return redirect()->back()->with('info', 'Content generation is already in progress!');
        }

        GenerateStateContentJob::dispatch($state);

        return redirect()->back()->with('success', 'Content generation started in background!');
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
