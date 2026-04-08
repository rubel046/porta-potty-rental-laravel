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
        $censusFilePath = resource_path('seeders/2025_Gaz_place_national.txt');
        $zipDataFilePath = resource_path('seeders/us_cities_data.json');

        $cityZipData = [];
        if (file_exists($zipDataFilePath)) {
            $zipData = json_decode(file_get_contents($zipDataFilePath), true);
            if ($zipData && isset($zipData['cities'])) {
                foreach ($zipData['cities'] as $city) {
                    $key = strtolower($city['name'].'-'.strtolower($city['state']));
                    $cityZipData[$key] = $city;
                }
            }
        }

        if (! file_exists($censusFilePath)) {
            $this->command->error('Places file not found: '.$censusFilePath);

            return;
        }

        $handle = fopen($censusFilePath, 'r');
        $header = fgetcsv($handle, 0, '|');

        $stateCache = State::all()->keyBy('code');
        $citiesByState = [];
        $batchSize = 500;
        $total = 0;
        $enriched = 0;

        while (($row = fgetcsv($handle, 0, '|')) !== false) {
            $data = array_combine($header, $row);

            $stateCode = $data['USPS'] ?? null;
            $name = $data['NAME'] ?? null;
            $lat = $data['INTPTLAT'] ?? null;
            $long = $data['INTPTLONG'] ?? null;

            if (! $stateCode || ! $name || ! $stateCache->has($stateCode)) {
                continue;
            }

            $name = preg_replace('/\s+(city|town|village|CDP|boro|burg|metro|urban county)$/i', '', $name);
            $slug = Str::slug($name.'-'.strtolower($stateCode));
            $slug = substr($slug, 0, 100);

            $key = strtolower($name.'-'.strtolower($stateCode));
            $enrichedData = $cityZipData[$key] ?? null;

            if ($enrichedData) {
                $zipCodes = $enrichedData['zip_codes'] ?? $this->generateZipCodes($stateCode);
                $nearbyCities = $enrichedData['nearby'] ?? [];
                $enriched++;
            } else {
                $zipCodes = $this->generateZipCodes($stateCode);
                $nearbyCities = $this->getNearbyCitiesFromState($stateCode, $citiesByState);
            }

            $cities[] = [
                'state_id' => $stateCache->get($stateCode)->id,
                'name' => $name,
                'slug' => $slug,
                'population' => 0,
                'latitude' => $lat ? (float) $lat : null,
                'longitude' => $long ? (float) $long : null,
                'zip_codes' => $zipCodes,
                'nearby_cities' => $nearbyCities,
                'is_active' => false,
            ];

            $citiesByState[$stateCode][] = $name;

            if (count($cities) >= $batchSize) {
                $this->insertCities($cities, $citiesByState);
                $total += count($cities);
                $cities = [];
            }
        }

        if (count($cities) > 0) {
            $this->insertCities($cities, $citiesByState);
            $total += count($cities);
        }

        fclose($handle);

        $this->updateNearbyCitiesForAllCities();

        $this->command->info("✅ Seeded {$total} US cities from Census data");
        $this->command->info("   - {$enriched} cities with enriched zip codes and nearby cities from data file");
    }

    private function generateZipCodes(string $stateCode): array
    {
        $stateZipRanges = [
            'AL' => ['35000', '36999'],
            'AK' => ['99500', '99999'],
            'AZ' => ['85000', '86599'],
            'AR' => ['71600', '72999'],
            'CA' => ['90000', '96199'],
            'CO' => ['80000', '81699'],
            'CT' => ['06000', '06999'],
            'DE' => ['19700', '19999'],
            'DC' => ['20000', '20599'],
            'FL' => ['32000', '33999'],
            'GA' => ['30000', '31999'],
            'HI' => ['96700', '96899'],
            'ID' => ['83200', '83899'],
            'IL' => ['60000', '62999'],
            'IN' => ['46000', '47999'],
            'IA' => ['50000', '52899'],
            'KS' => ['66000', '67999'],
            'KY' => ['40000', '42799'],
            'LA' => ['70000', '71499'],
            'ME' => ['03900', '04999'],
            'MD' => ['20600', '21999'],
            'MA' => ['01000', '02799'],
            'MI' => ['48000', '49999'],
            'MN' => ['55000', '56799'],
            'MS' => ['38600', '39799'],
            'MO' => ['63000', '65899'],
            'MT' => ['59000', '59999'],
            'NE' => ['68000', '69399'],
            'NV' => ['88900', '89899'],
            'NH' => ['03000', '03899'],
            'NJ' => ['07000', '08999'],
            'NM' => ['87000', '88499'],
            'NY' => ['10000', '14999'],
            'NC' => ['27000', '28999'],
            'ND' => ['58000', '58899'],
            'OH' => ['43000', '45999'],
            'OK' => ['73000', '74999'],
            'OR' => ['97000', '97999'],
            'PA' => ['15000', '19699'],
            'RI' => ['02800', '02999'],
            'SC' => ['29000', '29999'],
            'SD' => ['57000', '57799'],
            'TN' => ['37000', '38599'],
            'TX' => ['75000', '79999'],
            'UT' => ['84000', '84799'],
            'VT' => ['05000', '05999'],
            'VA' => ['22000', '24699'],
            'WA' => ['98000', '99499'],
            'WV' => ['24700', '26899'],
            'WI' => ['53000', '54999'],
            'WY' => ['82000', '83199'],
        ];

        if (! isset($stateZipRanges[$stateCode])) {
            return [];
        }

        $range = $stateZipRanges[$stateCode];
        $start = (int) $range[0];
        $end = (int) $range[1];

        $zips = [];
        $count = min(15, $end - $start);
        $step = max(1, (int) (($end - $start) / $count));

        for ($i = 0; $i < $count; $i++) {
            $zip = $start + ($i * $step);
            if ($zip > $end) {
                break;
            }
            $zips[] = (string) $zip;
        }

        return $zips;
    }

    private function insertCities(array $cities, array $citiesByState): void
    {
        foreach ($cities as $city) {
            $slug = $city['slug'];
            unset($city['slug']);

            City::updateOrCreate(
                ['slug' => $slug],
                $city
            );
        }
    }

    private function matchNearbyCities(array $nearbyNames, array $stateCities): array
    {
        $stateCitiesLower = array_map('strtolower', $stateCities);
        $matched = [];

        foreach ($nearbyNames as $name) {
            $nameLower = strtolower($name);
            $index = array_search($nameLower, $stateCitiesLower);
            if ($index !== false) {
                $matched[] = $stateCities[$index];
            }
        }

        return $matched;
    }

    private function getNearbyCitiesFromState(string $stateCode, array $citiesByState): array
    {
        if (! isset($citiesByState[$stateCode]) || count($citiesByState[$stateCode]) < 10) {
            return [];
        }

        $stateCities = $citiesByState[$stateCode];

        if (count($stateCities) <= 10) {
            return [];
        }

        shuffle($stateCities);

        return array_slice($stateCities, 0, 10);
    }

    private function updateNearbyCitiesForAllCities(): void
    {
        $states = State::all();
        foreach ($states as $state) {
            $stateCities = City::where('state_id', $state->id)
                ->where(function ($q) {
                    $q->whereNull('nearby_cities')
                        ->orWhere('nearby_cities', '[]')
                        ->orWhere('nearby_cities', 'like', '%'.'null'.'%');
                })
                ->pluck('name')
                ->toArray();

            if (count($stateCities) < 10) {
                continue;
            }

            shuffle($stateCities);
            $nearbyCities = array_slice($stateCities, 0, 10);

            City::where('state_id', $state->id)
                ->where(function ($q) {
                    $q->whereNull('nearby_cities')
                        ->orWhere('nearby_cities', '[]')
                        ->orWhere('nearby_cities', 'like', '%'.'null'.'%');
                })
                ->update(['nearby_cities' => $nearbyCities]);
        }
    }
}
