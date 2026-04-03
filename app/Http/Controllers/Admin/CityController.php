<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use App\Services\ContentGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $states = State::orderBy('name')->get();

        $query = City::with('state')->withCount(['servicePages', 'callLogs']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $cities = $query->orderByDesc('is_active')
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
        $types = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

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

        // Generate FAQs for each service type
        foreach ($types as $type) {
            $faqs = $generator->generateFaqs($city, $type);
            foreach ($faqs as $i => $faq) {
                $city->faqs()->updateOrCreate(
                    [
                        'question' => $faq['question'],
                        'service_type' => $type,
                    ],
                    array_merge($faq, [
                        'service_type' => $type,
                        'sort_order' => $i,
                        'is_active' => true,
                    ])
                );
            }
        }

        // Generate testimonials for each service type
        foreach ($types as $type) {
            $testimonials = $generator->generateTestimonials($city, $type);
            foreach ($testimonials as $t) {
                $city->testimonials()->updateOrCreate(
                    [
                        'customer_name' => $t['customer_name'],
                        'service_type' => $type,
                    ],
                    array_merge($t, ['service_type' => $type])
                );
            }
        }

        return redirect()->back()
            ->with('success', 'Generated 8 pages, '.(8 * 5).' FAQs & '.(8 * 3)." testimonials for {$city->name}!");
    }

    public function deletePages(City $city)
    {
        $pageCount = $city->servicePages()->count();
        $faqCount = $city->faqs()->count();
        $testimonialCount = $city->testimonials()->count();

        $city->servicePages()->delete();
        $city->faqs()->delete();
        $city->testimonials()->delete();

        return redirect()->back()
            ->with('success', "Deleted {$pageCount} pages, {$faqCount} FAQs, and {$testimonialCount} testimonials for {$city->name}!");
    }

    public function importJson(Request $request, City $city)
    {
        $jsonData = $request->input('json_content');

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'Please paste JSON content');
        }

        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Invalid JSON format: '.json_last_error_msg());
        }

        $pagesCreated = 0;
        $pagesUpdated = 0;
        $errors = [];

        // Validate and import service pages
        if (isset($data['service_pages']) && is_array($data['service_pages'])) {
            foreach ($data['service_pages'] as $pageData) {
                try {
                    if (empty($pageData['service_type']) || empty($pageData['slug'])) {
                        $errors[] = 'Skipped page: missing service_type or slug';

                        continue;
                    }

                    $city->servicePages()->updateOrCreate(
                        ['slug' => $pageData['slug']],
                        [
                            'service_type' => $pageData['service_type'],
                            'h1_title' => $pageData['h1_title'] ?? null,
                            'meta_title' => $pageData['meta_title'] ?? null,
                            'meta_description' => $pageData['meta_description'] ?? null,
                            'content' => $pageData['content'] ?? null,
                            'word_count' => $pageData['word_count'] ?? (isset($pageData['content']) ? str_word_count(strip_tags($pageData['content'])) : 0),
                            'is_published' => $pageData['is_published'] ?? true,
                            'published_at' => isset($pageData['published_at']) ? Carbon::parse($pageData['published_at']) : now(),
                        ]
                    );

                    if ($city->servicePages()->where('slug', $pageData['slug'])->wasRecentlyCreated) {
                        $pagesCreated++;
                    } else {
                        $pagesUpdated++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error with page {$pageData['service_type']}: ".$e->getMessage();
                }
            }
        }

        // Import FAQs
        $faqsCreated = 0;
        if (isset($data['faqs']) && is_array($data['faqs'])) {
            foreach ($data['faqs'] as $i => $faqData) {
                if (empty($faqData['question']) || empty($faqData['answer'])) {
                    continue;
                }

                $city->faqs()->updateOrCreate(
                    ['question' => $faqData['question']],
                    [
                        'answer' => $faqData['answer'],
                        'service_type' => $faqData['service_type'] ?? 'general',
                        'sort_order' => $faqData['sort_order'] ?? $i,
                        'is_active' => $faqData['is_active'] ?? true,
                    ]
                );
                $faqsCreated++;
            }
        }

        // Import testimonials
        $testimonialsCreated = 0;
        if (isset($data['testimonials']) && is_array($data['testimonials'])) {
            foreach ($data['testimonials'] as $tData) {
                if (empty($tData['customer_name']) || empty($tData['content'])) {
                    continue;
                }

                $city->testimonials()->updateOrCreate(
                    ['customer_name' => $tData['customer_name']],
                    [
                        'content' => $tData['content'],
                        'rating' => $tData['rating'] ?? 5,
                        'service_type' => $tData['service_type'] ?? 'general',
                        'is_active' => $tData['is_active'] ?? true,
                    ]
                );
                $testimonialsCreated++;
            }
        }

        // Recalculate SEO scores for all pages
        $city->servicePages()->each(fn ($page) => $page->calculateSeoScore());

        $message = "Imported {$pagesCreated} pages, updated {$pagesUpdated} pages, {$faqsCreated} FAQs, {$testimonialsCreated} testimonials.";

        if (! empty($errors)) {
            $message .= ' Warnings: '.implode('; ', array_slice($errors, 0, 3));
        }

        return redirect()->back()->with('success', $message);
    }

    public function getSampleJson(City $city)
    {
        $samplePages = [];
        $types = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

        foreach ($types as $type) {
            $samplePages[] = [
                'service_type' => $type,
                'slug' => strtolower($city->name).'-'.$type.'-'.strtolower($city->state->code),
                'h1_title' => ucfirst($type)." Porta Potty Rental in {$city->name}, {$city->state->code} | Same-Day Delivery",
                'meta_title' => ucfirst($type)." Porta Potty Rental {$city->name} {$city->state->code} - Free Quote",
                'meta_description' => "Need portable toilet rental in {$city->name}, {$city->state->code}? We offer fast delivery for ".str_replace(['_', 'luxury', 'party'], ['', 'VIP', 'celebrations'], $type).'. Call now for free quote!',
                'content' => "<h2>Welcome to {$city->name}'s Premier Porta Potty Rental Service</h2><p>Your trusted source for portable sanitation in {$city->name}, {$city->state->code}.</p><h3>Our Services</h3><p>We provide top-quality portable toilets for construction sites, events, weddings, and more.</p>",
                'word_count' => 500,
                'is_published' => true,
            ];
        }

        $sampleFaqs = [
            [
                'question' => "How much does porta potty rental cost in {$city->name}?",
                'answer' => 'Our rental prices start at $150/week for standard units. Contact us for a free quote tailored to your needs.',
                'service_type' => 'general',
                'sort_order' => 0,
            ],
            [
                'question' => "Do you offer same-day delivery in {$city->name}?",
                'answer' => 'Yes! We offer same-day delivery for orders placed before noon. Call us to check availability.',
                'service_type' => 'general',
                'sort_order' => 1,
            ],
        ];

        $sampleTestimonials = [
            [
                'customer_name' => 'John D.',
                'content' => "Great service in {$city->name}! The units were clean and delivered on time.",
                'rating' => 5,
                'service_type' => 'general',
            ],
            [
                'customer_name' => 'Sarah M.',
                'content' => 'Excellent experience for our wedding. Highly recommend!',
                'rating' => 5,
                'service_type' => 'wedding',
            ],
        ];

        $sample = [
            'city' => [
                'name' => $city->name,
                'state' => $city->state->code,
            ],
            'service_pages' => $samplePages,
            'faqs' => $sampleFaqs,
            'testimonials' => $sampleTestimonials,
        ];

        return response()->json($sample, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
