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
            $status = $domain->id === 1 ? false : true;
            foreach ($cityIds as $cityId) {
                DomainCity::firstOrCreate(
                    ['domain_id' => $domain->id, 'city_id' => $cityId],
                    ['status' => $status]
                );
            }
        }

        $this->command->info('✅ Seeded domain_cities for '.count($domains).' domains and '.$cityIds->count().' cities');
    }
}
