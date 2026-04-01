<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pricing & Costs', 'slug' => 'pricing-costs', 'description' => 'Porta potty rental pricing guides and cost breakdowns'],
            ['name' => 'Event Planning', 'slug' => 'event-planning', 'description' => 'Tips for planning portable restrooms at events'],
            ['name' => 'Construction', 'slug' => 'construction', 'description' => 'Construction site sanitation guides and OSHA requirements'],
            ['name' => 'Weddings', 'slug' => 'weddings', 'description' => 'Wedding portable restroom planning and tips'],
            ['name' => 'Guides & Tips', 'slug' => 'guides-tips', 'description' => 'General porta potty rental guides and helpful tips'],
            ['name' => 'Industry News', 'slug' => 'industry-news', 'description' => 'Portable sanitation industry news and updates'],
        ];

        foreach ($categories as $i => $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['sort_order' => $i])
            );
        }
    }
}
