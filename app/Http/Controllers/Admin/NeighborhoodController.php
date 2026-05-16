<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateNeighborhoodContentJob;
use App\Models\Domain;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NeighborhoodController extends Controller
{
    public function index(): View
    {
        $domain = Domain::current() ?? Domain::first();
        $domainId = $domain?->id;

        $neighborhoods = Neighborhood::with(['city.state'])
            ->withCount(['servicePages' => function ($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            }])
            ->orderByDesc('priority')
            ->orderBy('name')
            ->paginate(30);

        $totalNeighborhoods = Neighborhood::count();
        $withContent = Neighborhood::whereHas('servicePages', function ($q) use ($domainId) {
            $q->where('domain_id', $domainId)->where('is_published', true);
        })->count();

        return view('admin.neighborhoods.index', compact(
            'neighborhoods', 'totalNeighborhoods', 'withContent', 'domain'
        ));
    }

    public function show(Neighborhood $neighborhood): View
    {
        $domain = Domain::current() ?? Domain::first();
        $neighborhood->load(['city.state', 'servicePages' => function ($q) use ($domain) {
            $q->where('domain_id', $domain?->id);
        }]);

        return view('admin.neighborhoods.show', compact('neighborhood', 'domain'));
    }

    public function generate(Neighborhood $neighborhood, Request $request)
    {
        $domain = Domain::current() ?? Domain::first();

        $types = $request->input('types', $domain?->getServiceTypes() ?? ['general']);
        if (is_string($types)) {
            $types = explode(',', $types);
        }

        GenerateNeighborhoodContentJob::dispatch($neighborhood, $domain, $types);

        return redirect()->back()
            ->with('success', "Dispatched content generation for {$neighborhood->name} (" . count($types) . " service types).");
    }

    public function bulkGenerate(Request $request)
    {
        $domain = Domain::current() ?? Domain::first();
        $limit = (int) $request->input('limit', 10);
        $neighborhoods = Neighborhood::whereDoesntHave('servicePages', function ($q) use ($domain) {
            $q->where('domain_id', $domain?->id)->where('is_published', true);
        })->limit($limit)->get();

        $types = $domain?->getServiceTypes() ?? ['general'];
        $dispatched = 0;

        foreach ($neighborhoods as $nb) {
            GenerateNeighborhoodContentJob::dispatch($nb, $domain, $types);
            $dispatched++;
        }

        return redirect()->back()
            ->with('success', "Dispatched content generation for {$dispatched} neighborhoods.");
    }

    public function toggle(Neighborhood $neighborhood)
    {
        $neighborhood->update(['is_active' => !$neighborhood->is_active]);

        return redirect()->back()
            ->with('success', $neighborhood->is_active ? 'Neighborhood activated.' : 'Neighborhood deactivated.');
    }
}
