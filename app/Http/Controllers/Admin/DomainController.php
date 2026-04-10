<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainCity;
use App\Models\DomainState;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = Domain::withCount(['domainCities', 'domainStates'])->get();

        return view('admin.domains.index', compact('domains'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'business_name' => 'nullable|string|max:255',
            'primary_keyword' => 'nullable|string|max:255',
            'primary_service' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'cta_phone' => 'nullable|string|max:20',
            'primary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $validated['secondary_keywords'] = $this->parseCsvField($request->input('secondary_keywords_text'));
        $validated['service_types'] = $this->parseCsvField($request->input('service_types_text'));

        $domain = Domain::create($validated);

        $this->syncDomainRelations($domain);

        return redirect()
            ->route('admin.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    public function update(Request $request, Domain $domain): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain,'.$domain->id,
            'business_name' => 'nullable|string|max:255',
            'primary_keyword' => 'nullable|string|max:255',
            'primary_service' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'cta_phone' => 'nullable|string|max:20',
            'primary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $validated['secondary_keywords'] = $this->parseCsvField($request->input('secondary_keywords_text'));
        $validated['service_types'] = $this->parseCsvField($request->input('service_types_text'));

        $domain->update($validated);

        return redirect()
            ->route('admin.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        DomainCity::where('domain_id', $domain->id)->delete();
        DomainState::where('domain_id', $domain->id)->delete();

        $domain->delete();

        return redirect()
            ->route('admin.domains.index')
            ->with('success', 'Domain deleted successfully.');
    }

    public function switch(Domain $domain): RedirectResponse
    {
        Domain::setCurrent($domain);

        return redirect()
            ->back()
            ->with('success', "Switched to {$domain->name}");
    }

    public function sync(Domain $domain): RedirectResponse
    {
        $this->syncDomainRelations($domain);

        return redirect()
            ->route('admin.domains.index')
            ->with('success', "Synced cities and states for {$domain->name}");
    }

    private function syncDomainRelations(Domain $domain): void
    {
        $states = State::all();
        $existingStateIds = DomainState::where('domain_id', $domain->id)->pluck('state_id')->toArray();

        foreach ($states as $state) {
            if (! in_array($state->id, $existingStateIds)) {
                DomainState::create([
                    'domain_id' => $domain->id,
                    'state_id' => $state->id,
                    'status' => false,
                ]);
            }
        }

        $cities = City::all();
        $existingCityIds = DomainCity::where('domain_id', $domain->id)->pluck('city_id')->toArray();

        City::chunk(500, function ($citiesChunk) use ($domain, &$existingCityIds) {
            $records = [];
            foreach ($citiesChunk as $city) {
                if (! in_array($city->id, $existingCityIds)) {
                    $records[] = [
                        'domain_id' => $domain->id,
                        'city_id' => $city->id,
                        'status' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $existingCityIds[] = $city->id;
                }
            }
            if (! empty($records)) {
                DomainCity::insert($records);
            }
        });
    }

    private function parseCsvField(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $value)),
            fn ($item) => $item !== ''
        );
    }
}
