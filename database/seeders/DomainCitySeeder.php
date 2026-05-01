<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Domain;
use App\Models\DomainCity;
use Illuminate\Database\Seeder;

class DomainCitySeeder extends Seeder
{
    public function run(): void
    {
        $domains = Domain::where('is_active', true)->get();
        $cityIds = City::pluck('id');

        foreach ($domains as $domain) {
            foreach ($cityIds as $cityId) {
                DomainCity::firstOrCreate(
                    ['domain_id' => $domain->id, 'city_id' => $cityId],
                    ['status' => true]
                );
            }
        }

        $this->command->info('✅ Seeded domain_cities for '.$domains->count().' domains and '.$cityIds->count().' cities');
    }
}
