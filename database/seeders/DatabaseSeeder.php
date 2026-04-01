<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Porta Potty Rental database seeding...');
        $this->command->newLine();

        $this->call([
            StateSeeder::class,
            CitySeeder::class,
            BlogCategorySeeder::class,
            BuyerSeeder::class,
            ServicePageSeeder::class,  // This generates pages, FAQs, testimonials
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding complete!');

    }
}
