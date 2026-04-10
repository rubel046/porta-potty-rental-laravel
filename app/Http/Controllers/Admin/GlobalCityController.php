<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCityContentJob;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainCity;
use App\Models\DomainState;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class GlobalCityController extends Controller
{
    public function index(Request $request): Response
    {
        $states = State::orderBy('name')->get();

        $query = City::with('state');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        $cities = $query->orderBy('name')->paginate(30);

        return response(view('admin.global-cities.index', compact('cities', 'states')));
    }

    public function create(): Response
    {
        $states = State::orderBy('name')->get();

        return response(view('admin.global-cities.create', compact('states')));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'population' => 'nullable|integer',
            'zip_codes' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
        ]);

        $state = State::find($validated['state_id']);
        $slug = strtolower(str_replace(' ', '-', $validated['name'])).'-'.strtolower($state->code);

        $validated['slug'] = $slug;

        if (! empty($validated['zip_codes'])) {
            $validated['zip_codes'] = array_map(
                'trim',
                explode(',', $validated['zip_codes'])
            );
        }

        if (! empty($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $city = City::create($validated);

        $domains = Domain::where('is_active', true)->get();
        foreach ($domains as $domain) {
            DomainCity::firstOrCreate(
                ['domain_id' => $domain->id, 'city_id' => $city->id],
                ['status' => false]
            );

            DomainState::firstOrCreate(
                ['domain_id' => $domain->id, 'state_id' => $city->state_id],
                ['status' => false]
            );
        }

        if ($request->has('generate_pages')) {
            $this->generatePages($city);

            return redirect()->route('admin.global.cities.index')
                ->with('success', "City '{$city->name}' created with service pages!");
        }

        return redirect()->route('admin.global.cities.index')
            ->with('success', "City '{$city->name}' created!");
    }

    public function show(City $city): Response
    {
        $city->load(['state', 'servicePages', 'phoneNumbers', 'callLogs', 'blogPosts']);

        $cacheKey = "city_content_generation_{$city->id}";
        $generationStatus = Cache::get("{$cacheKey}_status", 'idle');
        $generationProgress = Cache::get("{$cacheKey}_progress", 0);
        $currentType = Cache::get("{$cacheKey}_current_type");
        $generationErrors = Cache::get("{$cacheKey}_errors", []);
        $startedAt = Cache::get("{$cacheKey}_started_at");

        if ($generationStatus === 'completed' || $generationStatus === 'failed') {
            Cache::forget("{$cacheKey}_status");
            Cache::forget("{$cacheKey}_progress");
            Cache::forget("{$cacheKey}_current_type");
            Cache::forget("{$cacheKey}_errors");
            Cache::forget("{$cacheKey}_started_at");
            $generationStatus = 'idle';
            $generationProgress = 0;
            $generationErrors = [];
        }

        return response(view('admin.global-cities.show', compact('city', 'generationStatus', 'generationProgress', 'currentType', 'generationErrors', 'startedAt')));
    }

    public function edit(City $city): Response
    {
        $states = State::orderBy('name')->get();
        $city->load('servicePages', 'phoneNumbers');

        return response(view('admin.global-cities.edit', compact('city', 'states')));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'population' => 'nullable|integer',
            'zip_codes' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
        ]);

        if (! empty($validated['zip_codes']) && is_string($validated['zip_codes'])) {
            $validated['zip_codes'] = array_map(
                'trim',
                explode(',', $validated['zip_codes'])
            );
        }

        if (! empty($validated['nearby_cities']) && is_string($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $city->update($validated);

        return redirect()->route('admin.global.cities.index')
            ->with('success', "City '{$city->name}' updated!");
    }

    public function destroy(City $city): RedirectResponse
    {
        $name = $city->name;

        DomainCity::where('city_id', $city->id)->delete();

        $city->delete();

        return redirect()->route('admin.global.cities.index')
            ->with('success', "City '{$name}' deleted!");
    }

    public function generatePages(City $city): void
    {
        $cacheKey = "city_content_generation_{$city->id}";

        if (Cache::get("{$cacheKey}_status") === 'processing') {
            return;
        }

        Cache::put("{$cacheKey}_status", 'processing', now()->addMinutes(30));
        Cache::put("{$cacheKey}_progress", 0, now()->addMinutes(30));
        Cache::put("{$cacheKey}_current_type", null, now()->addMinutes(30));
        Cache::put("{$cacheKey}_started_at", now()->toIso8601String(), now()->addMinutes(60));

        GenerateCityContentJob::dispatch($city);
    }

    public function generationProgress(City $city): JsonResponse
    {
        $cacheKey = "city_content_generation_{$city->id}";

        return response()->json([
            'status' => Cache::get("{$cacheKey}_status", 'idle'),
            'progress' => Cache::get("{$cacheKey}_progress", 0),
            'current_type' => Cache::get("{$cacheKey}_current_type"),
            'started_at' => Cache::get("{$cacheKey}_started_at"),
        ]);
    }

    public function deletePages(City $city): RedirectResponse
    {
        $pageCount = $city->servicePages()->count();
        $faqCount = $city->faqs()->count();
        $testimonialCount = $city->testimonials()->count();

        $city->servicePages()->delete();
        $city->faqs()->delete();
        $city->testimonials()->delete();

        return redirect()->back()
            ->with('success', "Deleted {$pageCount} pages, {$faqCount} FAQs, and {$testimonialCount} testimonials!");
    }
}
