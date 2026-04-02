<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AllUSCitiesSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = resource_path('seeders/2025_Gaz_place_national.txt');

        if (! file_exists($filePath)) {
            $this->command->error('Places file not found: '.$filePath);

            return;
        }

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle, 0, '|');

        $stateCache = State::all()->keyBy('code');

        $cities = [];
        $batchSize = 500;
        $total = 0;

        while (($row = fgetcsv($handle, 0, '|')) !== false) {
            $data = array_combine($header, $row);

            $stateCode = $data['USPS'] ?? null;
            $name = $data['NAME'] ?? null;
            $lsad = $data['LSAD'] ?? null;
            $lat = $data['INTPTLAT'] ?? null;
            $long = $data['INTPTLONG'] ?? null;

            if (! $stateCode || ! $name || ! $stateCache->has($stateCode)) {
                continue;
            }

            // Strip suffixes like "city", "town", "village", "CDP" from name
            $name = preg_replace('/\s+(city|town|village|CDP|boro|burg|metro|urban county)$/i', '', $name);

            $slug = Str::slug($name.'-'.strtolower($stateCode));
            $slug = substr($slug, 0, 100);

            $cities[] = [
                'state_id' => $stateCache->get($stateCode)->id,
                'name' => $name,
                'slug' => $slug,
                'population' => 0,
                'latitude' => $lat ? (float) $lat : null,
                'longitude' => $long ? (float) $long : null,
                'is_active' => false,
            ];

            if (count($cities) >= $batchSize) {
                $this->insertCities($cities);
                $total += count($cities);
                $cities = [];
            }
        }

        if (count($cities) > 0) {
            $this->insertCities($cities);
            $total += count($cities);
        }

        fclose($handle);

        $this->command->info("✅ Seeded {$total} US cities from Census data (all inactive)");
    }

    private function insertCities(array $cities): void
    {
        foreach ($cities as $city) {
            City::updateOrCreate(
                ['slug' => $city['slug']],
                $city
            );
        }
    }
}
