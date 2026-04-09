<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainCity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = Domain::withCount('domainCities')->get();

        return view('admin.domains.index', compact('domains'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'primary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $domain = Domain::create($validated);

        City::chunk(1000, function ($cities) use ($domain) {
            $records = $cities->map(fn ($city) => [
                'domain_id' => $domain->id,
                'city_id' => $city->id,
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            DomainCity::insert($records);
        });

        return redirect()
            ->route('admin.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    public function update(Request $request, Domain $domain): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain,'.$domain->id,
            'primary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $domain->update($validated);

        return redirect()
            ->route('admin.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        DomainCity::where('domain_id', $domain->id)->delete();

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
}
