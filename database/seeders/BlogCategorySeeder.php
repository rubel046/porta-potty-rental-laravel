<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Domain;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $domains = Domain::all();

        if ($domains->isEmpty()) {
            $this->seedDefaultCategories(null);

            return;
        }

        foreach ($domains as $domain) {
            $this->seedDefaultCategories($domain->id);
        }
    }

    private function seedDefaultCategories(?int $domainId): void
    {
        $categories = [
            ['name' => 'Pricing & Costs', 'slug' => 'pricing-costs', 'description' => 'Porta potty rental pricing guides and cost breakdowns', 'icon' => '💰'],
            ['name' => 'Event Planning', 'slug' => 'event-planning', 'description' => 'Tips for planning portable restrooms at events', 'icon' => '🎉'],
            ['name' => 'Construction', 'slug' => 'construction', 'description' => 'Construction site sanitation guides and OSHA requirements', 'icon' => '🏗️'],
            ['name' => 'Weddings', 'slug' => 'weddings', 'description' => 'Wedding portable restroom planning and tips', 'icon' => '💒'],
            ['name' => 'Guides & Tips', 'slug' => 'guides-tips', 'description' => 'General porta potty rental guides and helpful tips', 'icon' => '📋'],
            ['name' => 'Industry News', 'slug' => 'industry-news', 'description' => 'Portable sanitation industry news and updates', 'icon' => '📰'],
            ['name' => 'Emergency Services', 'slug' => 'emergency-services', 'description' => 'Emergency portable toilet rental information', 'icon' => '🚨'],
            ['name' => 'Residential', 'slug' => 'residential', 'description' => 'Home renovation and residential porta potty rentals', 'icon' => '🏠'],
            ['name' => 'Luxury Restrooms', 'slug' => 'luxury-restrooms', 'description' => 'Luxury restroom trailer rentals for VIP events', 'icon' => '✨'],
            ['name' => 'Health & Safety', 'slug' => 'health-safety', 'description' => 'Sanitation standards and health safety guidelines', 'icon' => '🧼'],
            ['name' => 'Seasonal', 'slug' => 'seasonal', 'description' => 'Winter and summer porta potty considerations', 'icon' => '☀️'],
            ['name' => 'Locations', 'slug' => 'locations', 'description' => 'City-specific porta potty rental guides', 'icon' => '📍'],
        ];

        foreach ($categories as $i => $category) {
            $slug = $domainId ? "{$category['slug']}-{$domainId}" : $category['slug'];

            BlogCategory::updateOrCreate(
                ['slug' => $slug, 'domain_id' => $domainId],
                array_merge($category, ['sort_order' => $i, 'domain_id' => $domainId])
            );
        }
    }
}
