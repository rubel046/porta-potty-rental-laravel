<?php

namespace App\Console\Commands;

use App\Jobs\GenerateCityContentJob;
use App\Models\City;
use App\Models\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateCityServicePagesDaily extends Command
{
    protected $signature = 'city:generate-daily-pages {--domain= : Specific domain ID}';

    protected $description = 'Generate service pages for cities that failed or are incomplete';

    public function handle(): int
    {
        $maxCitiesPerDay = (int) env('DAILY_CITY_PAGE_GENERATION', 5);

        $domainId = $this->option('domain');

        $domains = Domain::where('is_active', true)
            ->when($domainId, fn ($q) => $q->where('id', $domainId))
            ->get();

        if ($domains->isEmpty()) {
            $this->info('No active domains found.');

            return Command::SUCCESS;
        }

        $this->info("Processing {$domains->count()} domain(s) - max {$maxCitiesPerDay} cities each...");

        $totalGenerated = 0;

        foreach ($domains as $domain) {
            $generated = $this->generateForDomain($domain, $maxCitiesPerDay);
            $totalGenerated += $generated;
        }

        $this->info("Generated service pages for {$totalGenerated} cities today.");

        return Command::SUCCESS;
    }

    protected function generateForDomain(Domain $domain, int $maxCities): int
    {
        $this->info("Processing domain: {$domain->domain}");

        try {
            session(['current_domain_id' => $domain->id]);
        } catch (\Exception $e) {
            // Continue without session
        }

        $serviceTypes = $domain->service_types ?? [];

        if (empty($serviceTypes)) {
            $this->warn("  No service types configured for domain {$domain->domain}");

            return 0;
        }

        // Get cities that need generation for this domain
        $cities = $this->getCitiesNeedingGeneration($domain, $serviceTypes, $maxCities);

        if ($cities->isEmpty()) {
            $this->info("  All cities have complete service pages for {$domain->domain}");

            return 0;
        }

        $this->info("  Found {$cities->count()} cities needing generation");

        $generatedCities = [];

        foreach ($cities as $city) {
            $this->info("    Queueing generation for: {$city->name}, {$city->state?->code}");

            // Dispatch to queue
            GenerateCityContentJob::dispatch($city, $domain);

            $generatedCities[] = $city->id;

            // Sleep between cities (30 seconds)
            sleep(30);

            if (count($generatedCities) >= $maxCities) {
                $this->warn("    Reached daily limit ({$maxCities})");
                break;
            }
        }

        // Activate generated cities after successful queueing
        if (! empty($generatedCities)) {
            $this->activateCities($generatedCities, $domain);
        }

        return count($generatedCities);
    }

    protected function getCitiesNeedingGeneration(Domain $domain, array $serviceTypes, int $limit)
    {
        $domainId = $domain->id;
        $requiredCount = count($serviceTypes);

        // Get city IDs that have fewer than required service pages or have failed/pending/processing status
        $cityIdsWithPages = DB::table('service_pages')
            ->select('city_id')
            ->where('domain_id', $domainId)
            ->whereIn('service_type', $serviceTypes)
            ->groupBy('city_id')
            ->havingRaw('COUNT(*) < ?', [$requiredCount])
            ->orHavingRaw('MAX(generation_status) IN (?, ?, ?)', ['failed', 'pending', 'processing'])
            ->pluck('city_id')
            ->toArray();

        // Get cities that are linked to this domain but NOT in the above list (need generation)
        $cityIdsNeeded = DB::table('domain_cities')
            ->where('domain_id', $domainId)
            ->whereNotIn('city_id', $cityIdsWithPages)
            ->pluck('city_id')
            ->toArray();

        // Get the city models for those IDs (limited)
        return City::whereIn('id', array_slice($cityIdsNeeded, 0, $limit))
            ->with('state')
            ->get();
    }

    protected function activateCities(array $cityIds, Domain $domain): void
    {
        $domainId = $domain->id;

        foreach ($cityIds as $cityId) {
            $city = City::find($cityId);
            if ($city) {
                // Activate the city
                $city->update(['is_active' => true]);

                // Update domain_city relation status via pivot table
                DB::table('domain_cities')
                    ->where('city_id', $cityId)
                    ->where('domain_id', $domainId)
                    ->update(['status' => true]);

                $this->info("    Activated city: {$city->name}");
            }
        }
    }
}
