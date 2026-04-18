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

        // Get city IDs where content_generated is false or null
        $cityIdsNeeded = DB::table('domain_cities')
            ->where('domain_id', $domainId)
            ->where(function ($query) {
                $query->where('content_generated', false)
                    ->orWhereNull('content_generated');
            })
            ->take($limit)
            ->pluck('city_id')
            ->toArray();

        if (empty($cityIdsNeeded)) {
            return collect();
        }

        return City::whereIn('id', $cityIdsNeeded)
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
