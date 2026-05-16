<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\State;
use App\Services\WikipediaService;
use Illuminate\Console\Command;

class EnrichCityContext extends Command
{
    protected $signature = 'city:enrich-context
        {--limit=100 : Max cities to process (0 = all)}
        {--state= : Only process cities in a specific state code (e.g. "TX")}
        {--batch=50 : Cities per batch before deferring}
        {--dry-run : Show what would be done without writing}';

    protected $description = 'Fetch Wikipedia data to enrich city context fields for better AI-generated content';

    public function handle(WikipediaService $wikipedia): int
    {
        $limit = (int) $this->option('limit');
        $batchSize = (int) $this->option('batch');
        $dryRun = $this->option('dry-run');

        $query = City::whereNull('city_description')
            ->orWhere('city_description', '')
            ->with('state');

        if ($stateCode = $this->option('state')) {
            $query->whereHas('state', fn ($q) => $q->where('code', strtoupper($stateCode)));
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->info('All cities already have context data. Nothing to do.');

            return self::SUCCESS;
        }

        $processCount = ($limit > 0) ? min($limit, $total) : $total;
        $this->info("Found {$total} cities missing context data. Processing {$processCount}.");

        if ($dryRun) {
            $this->warn("Dry run — no data will be written.");

            return self::SUCCESS;
        }

        if (!$this->confirm("Process {$processCount} cities? This will make {$processCount} Wikipedia API calls.")) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $cities = $query->limit($processCount)->get();
        $bar = $this->output->createProgressBar($cities->count());
        $bar->start();

        $enriched = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($cities as $city) {
            $stateName = $city->state?->name ?? '';
            $stateCode = $city->state?->code ?? '';

            // Map common short names so Wikipedia finds them
            $searchName = $this->getWikipediaCityName($city->name, $stateCode);

            $data = $wikipedia->getCityData($searchName, $stateName);

            if (!$data['success']) {
                $skipped++;
                $bar->advance();
                usleep(100000); // 100ms rate limit
                continue;
            }

            $description = $data['description'] ?? '';
            $economyText = $data['economy'] ?? '';
            $climateText = $data['climate'] ?? '';

            // Combine economy + geography into city_description if empty
            if (empty($description)) {
                $descriptionParts = [];
                if (!empty($data['geography'])) {
                    $descriptionParts[] = $data['geography'];
                }
                if (!empty($economyText)) {
                    $descriptionParts[] = $economyText;
                }
                $description = implode(' ', $descriptionParts);
            }

            $updateData = [];

            if (!empty($description)) {
                $updateData['city_description'] = mb_substr($description, 0, 2000);
            }

            if (!empty($climateText)) {
                $updateData['climate_info'] = mb_substr($climateText, 0, 1000);
            }

            if (!empty($economyText)) {
                $existingInfo = $city->local_events ?? '';
                $combined = $existingInfo ? $existingInfo . "\n\n" . $economyText : $economyText;
                $updateData['local_events'] = mb_substr($combined, 0, 2000);
            }

            if (!empty($data['sports'] ?? '')) {
                $sports = $data['sports'];
                $existingConstruction = $city->construction_info ?? '';
                $combinedConstruction = $existingConstruction
                    ? $existingConstruction . "\n\nSports venues: " . $sports
                    : "Major venues: " . $sports;
                $updateData['construction_info'] = mb_substr($combinedConstruction, 0, 2000);
            }

            if (!empty($updateData)) {
                $city->update($updateData);
                $enriched++;
            }

            $bar->advance();
            usleep(200000); // 200ms between API calls
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Done! Enriched: {$enriched}, Skipped (no Wikipedia data): {$skipped}, Errors: {$errors}");

        if ($total > $processCount && $enriched > 0) {
            $remaining = $total - $processCount;
            $this->line("{$remaining} cities remaining. Run again to process more.");
            $this->line("Tip: php artisan city:enrich-context --state=TX to do one state at a time.");
        }

        return self::SUCCESS;
    }

    protected function getWikipediaCityName(string $name, string $stateCode): string
    {
        // Wikipedia disambiguation: some cities need state suffix for correct page
        $ambiguousCities = [
            'Columbus', 'Springfield', 'Portland', 'Richmond', 'Augusta',
            'Aurora', 'Rochester', 'Burlington', 'Manchester', 'Orange',
            'Newark', 'Cambridge', 'Fairfield', 'Franklin', 'Salem',
            'Madison', 'Moscow', 'Kingston', 'Lebanon', 'Durham',
            'Oxford', 'Clinton', 'Washington', 'Georgetown', 'Greenville',
        ];

        if (in_array($name, $ambiguousCities)) {
            return "{$name}, {$stateCode}";
        }

        return $name;
    }
}
