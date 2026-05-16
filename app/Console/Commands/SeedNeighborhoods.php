<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Neighborhood;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SeedNeighborhoods extends Command
{
    protected $signature = 'neighborhoods:seed
        {--state= : Only process cities in a state code}
        {--city= : Specific city name}
        {--limit=50 : Max cities to process}
        {--dry-run : Preview without writing}';

    protected $description = 'Seed neighborhoods from Wikipedia for all cities';

    protected ?Client $httpClient = null;

    protected function getClient(): Client
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => 15,
                'headers' => ['User-Agent' => 'PottyDirect/1.0 (neighborhood seeding)'],
            ]);
        }

        return $this->httpClient;
    }

    public function handle(): int
    {
        $query = City::with('state');


        if ($stateCode = $this->option('state')) {
            $query->whereHas('state', fn ($q) => $q->where('code', strtoupper($stateCode)));
        }

        if ($cityName = $this->option('city')) {
            $query->where('name', $cityName);
        }

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        // population is always 0 from seeder, so use zip_codes length as size proxy
        $cities = $query->orderByRaw('LENGTH(COALESCE(zip_codes, \'\')) DESC')->get();
        $totalNew = 0;
        $totalSkipped = 0;

        $this->info("Processing neighborhoods for {$cities->count()} cities...");

        if ($this->option('dry-run')) {
            $this->warn('Dry run — no data written.');
        }

        $bar = $this->output->createProgressBar($cities->count());
        $bar->start();

        foreach ($cities as $city) {
            $existingCount = Neighborhood::where('city_id', $city->id)->count();
            if ($existingCount > 0) {
                $totalSkipped++;
                $bar->advance();
                continue;
            }

            $neighborhoods = $this->fetchNeighborhoods($city->name, $city->state?->name ?? '');

            if (empty($neighborhoods)) {
                $totalSkipped++;
                $bar->advance();
                usleep(200000);
                continue;
            }

            if (!$this->option('dry-run')) {
                foreach ($neighborhoods as $nb) {
                    Neighborhood::firstOrCreate(
                        [
                            'city_id' => $city->id,
                            'slug' => Str::slug($nb['name'] . '-' . $city->slug),
                        ],
                        [
                            'name' => $nb['name'],
                            'description' => $nb['description'] ?? null,
                            'local_landmarks' => $nb['landmarks'] ?? null,
                            'neighborhood_type' => $nb['type'] ?? null,
                            'latitude' => $nb['latitude'] ?? null,
                            'longitude' => $nb['longitude'] ?? null,
                            'is_active' => true,
                            'priority' => $nb['priority'] ?? 0,
                        ]
                    );
                }
                $totalNew += count($neighborhoods);
            }

            $bar->advance();
            usleep(300000);
        }

        $bar->finish();
        $this->newLine(2);

        if ($this->option('dry-run')) {
            $this->info("Dry run complete. Would have seeded neighborhoods for cities.");
        } else {
            $this->info("Done! Created {$totalNew} neighborhoods across " . ($cities->count() - $totalSkipped) . " cities.");
            $this->line("{$totalSkipped} cities skipped (already have neighborhoods or no data found).");
        }

        return self::SUCCESS;
    }

    protected function fetchNeighborhoods(string $cityName, string $stateName): array
    {
        // Respect Wikipedia's rate limit (1 second between batches of calls)
        usleep(1100000);

        // First try to get the "List of neighborhoods in X" page
        $listPage = "List of neighborhoods in {$cityName}";
        $result = $this->tryFetchNeighborhoodList($listPage);

        if (!empty($result)) {
            return $result;
        }

        // Rate limit between retries (Wikipedia permits ~200 req/min for non-bot)
        usleep(1100000);

        // Try "Neighborhoods in X" or the city's geography section
        $result = $this->tryFetchFromCityPage($cityName);
        if (!empty($result)) {
            return $result;
        }

        return [];
    }

    protected function tryFetchNeighborhoodList(string $pageTitle): array
    {
        try {
            $response = $this->getClient()->get('https://en.wikipedia.org/w/api.php', [
                'query' => [
                    'action' => 'parse',
                    'page' => $pageTitle,
                    'prop' => 'text',
                    'format' => 'json',
                    'redirects' => 1,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $html = $data['parse']['text']['*'] ?? '';

            if (empty($html)) {
                return [];
            }

            return $this->parseNeighborhoodListHtml($html);
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function tryFetchFromCityPage(string $cityName): array
    {
        try {
            // Get the Cityscape or Geography section
            $response = $this->getClient()->get('https://en.wikipedia.org/w/api.php', [
                'query' => [
                    'action' => 'parse',
                    'page' => $cityName,
                    'prop' => 'sections',
                    'format' => 'json',
                    'redirects' => 1,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $sections = $data['parse']['sections'] ?? [];

            // Find Cityscape section index
            $targetSection = null;
            foreach ($sections as $section) {
                if (in_array($section['line'], ['Cityscape', 'Neighborhoods', 'Geography'])) {
                    $targetSection = $section['index'];
                    break;
                }
            }

            if (!$targetSection) {
                return [];
            }

            // Get section content
            $response = $this->getClient()->get('https://en.wikipedia.org/w/api.php', [
                'query' => [
                    'action' => 'parse',
                    'page' => $cityName,
                    'prop' => 'text',
                    'section' => $targetSection,
                    'format' => 'json',
                    'redirects' => 1,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $html = $data['parse']['text']['*'] ?? '';

            if (empty($html)) {
                return [];
            }

            return $this->parseNeighborhoodListHtml($html);
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function parseNeighborhoodListHtml(string $html): array
    {
        $neighborhoods = [];

        // Try to find UL/LI patterns that list neighborhoods
        // Wikipedia lists neighborhoods as bullet points or table rows

        // Pattern 1: <ul><li>Neighborhood Name</li></ul>
        if (preg_match_all('/<li><a[^>]*>([^<]+)<\/a>(?:\s*[-–—]\s*([^<]*))?<\/li>/i', $html, $matches)) {
            foreach ($matches[1] as $i => $name) {
                $name = trim(strip_tags($name));
                $name = html_entity_decode($name);

                // Filter out non-neighborhood entries
                if ($this->isValidNeighborhoodName($name)) {
                    $description = '';
                    if (!empty($matches[2][$i])) {
                        $description = trim(strip_tags($matches[2][$i]));
                    }

                    $neighborhoods[] = [
                        'name' => $name,
                        'description' => $description,
                        'landmarks' => null,
                        'type' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'priority' => 0,
                    ];
                }
            }
        }

        // Pattern 2: <td>Neighborhood Name</td> in tables
        if (count($neighborhoods) < 3) {
            if (preg_match_all('/<td[^>]*><a[^>]*>([^<]+)<\/a><\/td>/i', $html, $tdMatches)) {
                foreach ($tdMatches[1] as $name) {
                    $name = trim(strip_tags($name));
                    $name = html_entity_decode($name);

                    if ($this->isValidNeighborhoodName($name)) {
                        $neighborhoods[] = [
                            'name' => $name,
                            'description' => null,
                            'landmarks' => null,
                            'type' => null,
                            'latitude' => null,
                            'longitude' => null,
                            'priority' => 0,
                        ];
                    }
                }
            }
        }

        // Limit to 20 neighborhoods per city to keep things manageable
        $neighborhoods = array_slice($neighborhoods, 0, 20);

        // Remove duplicates
        $seen = [];
        $unique = [];
        foreach ($neighborhoods as $nb) {
            $key = Str::slug($nb['name']);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $nb;
            }
        }

        return $unique;
    }

    protected function isValidNeighborhoodName(string $name): bool
    {
        $name = trim($name);

        if (strlen($name) < 2 || strlen($name) > 80) {
            return false;
        }

        // Filter out non-neighborhood items
        $skipPatterns = [
            '/^\d/', '/^(see also|references|external|notes|coordinates)/i',
            '/^(downtown|midtown|uptown)$/i', // too generic alone
            '/^\d{3}$/', '/^(list of|map of|history|demographics|economy)/i',
            '/^(categories:|commons|wikimedia)/i',
        ];

        foreach ($skipPatterns as $pattern) {
            if (preg_match($pattern, $name)) {
                return false;
            }
        }

        return true;
    }
}
