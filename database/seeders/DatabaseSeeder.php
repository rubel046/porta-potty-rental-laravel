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
            AdminUserSeeder::class,
            StateSeeder::class,
            AllUSCitiesSeeder::class,
            //            CitySeeder::class,
            BlogCategorySeeder::class,
            //            BlogPostSeeder::class,
            BuyerSeeder::class,
            // FaqSeeder::class,
            // ServicePageSeeder::class,
            SiteSettingSeeder::class,
            // TestimonialSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding complete!');

    }
}
