<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $states = State::orderBy('name')->get();

        $cities = City::with('state')
            ->withCount(['servicePages', 'callLogs'])
            ->orderByDesc('priority')
            ->orderBy('name')
            ->paginate(30);

        return view('admin.cities.index', compact('cities', 'states'));
    }

    public function create()
    {
        $states = State::orderBy('name')->get();

        return view('admin.cities.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'area_codes' => 'nullable|string|max:100',
            'population' => 'nullable|integer',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
        ]);

        $state = State::find($validated['state_id']);
        $slug = strtolower(str_replace(' ', '-', $validated['name']))
            .'-'.strtolower($state->code);

        $validated['slug'] = $slug;

        if (! empty($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $city = City::create($validated);

        // Auto-generate pages if requested
        if ($request->has('generate_pages')) {
            $this->generatePages($city);

            return redirect()->route('admin.cities.index')
                ->with('success', "City '{$city->name}' created with service pages!");
        }

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$city->name}' created!");
    }

    public function edit(City $city)
    {
        $states = State::orderBy('name')->get();
        $city->load('servicePages', 'phoneNumbers');

        return view('admin.cities.edit', compact('city', 'states'));
    }

    public function show(City $city)
    {
        $city->load(['state', 'servicePages', 'phoneNumbers', 'callLogs', 'blogPosts']);

        return view('admin.cities.show', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'area_codes' => 'nullable|string|max:100',
            'population' => 'nullable|integer',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if (! empty($validated['nearby_cities']) && is_string($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$city->name}' updated!");
    }

    public function destroy(City $city)
    {
        $name = $city->name;
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$name}' deleted!");
    }

    public function generatePages(City $city)
    {
        $generator = new ContentGeneratorService;
        $types = ['general', 'construction', 'wedding', 'event'];

        foreach ($types as $type) {
            $data = $generator->generateServicePageContent($city, $type);

            $city->servicePages()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'service_type' => $data['service_type'],
                    'h1_title' => $data['h1_title'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'content' => $data['content'],
                    'word_count' => $data['word_count'],
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        // FAQs
        $faqs = $generator->generateFaqs($city);
        foreach ($faqs as $i => $faq) {
            $city->faqs()->updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['sort_order' => $i, 'is_active' => true])
            );
        }

        // Testimonials
        $testimonials = $generator->generateTestimonials($city);
        foreach ($testimonials as $t) {
            $city->testimonials()->updateOrCreate(
                ['customer_name' => $t['customer_name'], 'city_id' => $city->id],
                $t
            );
        }

        return redirect()->back()
            ->with('success', "Generated 4 pages, FAQs & testimonials for {$city->name}!");
    }
}
