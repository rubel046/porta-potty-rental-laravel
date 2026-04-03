<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Faq;
use App\Models\ServicePage;
use App\Models\Testimonial;
use App\Services\ContentGeneratorService;
use Illuminate\Database\Seeder;

class ServicePageSeeder extends Seeder
{
    public function run(): void
    {
        $generator = new ContentGeneratorService;
        $serviceTypes = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

        $cities = City::with('state')->where('is_active', true)->get();

        foreach ($cities as $city) {
            $this->command->info("Generating pages for {$city->name}, {$city->state->code}...");

            foreach ($serviceTypes as $type) {
                $data = $generator->generateServicePageContent($city, $type);

                $page = ServicePage::updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'city_id' => $city->id,
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

                // Schema markup generate
                $page->update([
                    'schema_markup' => $page->generateSchemaMarkup(),
                ]);

                // SEO score calculate
                $page->calculateSeoScore();
            }

            // FAQs generate
            $faqs = $generator->generateFaqs($city);
            foreach ($faqs as $i => $faqData) {
                Faq::updateOrCreate(
                    [
                        'city_id' => $city->id,
                        'question' => $faqData['question'],
                    ],
                    [
                        'answer' => $faqData['answer'],
                        'sort_order' => $i,
                        'is_active' => true,
                    ]
                );
            }

            // Testimonials generate
            $testimonials = $generator->generateTestimonials($city);
            foreach ($testimonials as $testimonialData) {
                Testimonial::updateOrCreate(
                    [
                        'city_id' => $city->id,
                        'customer_name' => $testimonialData['customer_name'],
                    ],
                    $testimonialData
                );
            }
        }

        $totalPages = ServicePage::count();
        $totalFaqs = Faq::count();
        $totalTestimonials = Testimonial::count();

        $this->command->info("✅ Generated {$totalPages} service pages");
        $this->command->info("✅ Generated {$totalFaqs} FAQs");
        $this->command->info("✅ Generated {$totalTestimonials} testimonials");
    }
}
