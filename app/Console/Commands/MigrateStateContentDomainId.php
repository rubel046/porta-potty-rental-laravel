<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class MigrateStateContentDomainId extends Command
{
    protected $signature = 'migrate:state-content-domain {domain_id=1}';

    protected $description = 'Set domain_id for existing testimonials and FAQs data';

    public function handle(): int
    {
        $domainId = (int) $this->argument('domain_id');
        $this->info("Setting domain_id = {$domainId} for all testimonials and FAQs...");

        // Update Testimonials
        $testimonialCount = 0;
        $testimonials = Testimonial::whereNull('domain_id')->orWhere('domain_id', '!=', $domainId)->get();
        foreach ($testimonials as $testimonial) {
            $testimonial->domain_id = $domainId;
            $testimonial->save();
            $testimonialCount++;
        }
        $this->info("Updated {$testimonialCount} testimonials.");

        // Update FAQs
        $faqCount = 0;
        $faqs = Faq::whereNull('domain_id')->orWhere('domain_id', '!=', $domainId)->get();
        foreach ($faqs as $faq) {
            $faq->domain_id = $domainId;
            $faq->save();
            $faqCount++;
        }
        $this->info("Updated {$faqCount} FAQs.");

        // Update City page_content
        $cityCount = 0;
        $cities = City::all();
        foreach ($cities as $city) {
            $updated = false;

            if ($city->page_content) {
                $content = $city->page_content;

                if (isset($content['faqs']) && is_array($content['faqs'])) {
                    foreach ($content['faqs'] as &$faq) {
                        if (! isset($faq['domain_id'])) {
                            $faq['domain_id'] = $domainId;
                            $updated = true;
                        }
                    }
                }

                if (isset($content['testimonials']) && is_array($content['testimonials'])) {
                    foreach ($content['testimonials'] as &$testimonial) {
                        if (! isset($testimonial['domain_id'])) {
                            $testimonial['domain_id'] = $domainId;
                            $updated = true;
                        }
                    }
                }

                if ($updated) {
                    $city->page_content = $content;
                    $city->save();
                    $cityCount++;
                    $this->line("Updated city: {$city->name}");
                }
            }
        }
        $this->info("Updated {$cityCount} city records.");

        $this->info('Done!');

        return Command::SUCCESS;
    }
}
