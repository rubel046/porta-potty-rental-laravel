<?php

namespace App\Console\Commands;

use App\Jobs\GenerateCityContentJob;
use App\Models\City;
use App\Models\Domain;
use Illuminate\Console\Command;

class RegenerateTopCities extends Command
{
    protected $signature = 'city:regenerate-top
        {--cities= : Comma-separated list of city slugs (defaults to top 10)}
        {--domain= : Specific domain ID}
        {--dry-run : Show which cities would be regenerated without running}';

    protected $description = 'Regenerate service pages for top cities with enhanced local context prompts';

    protected array $topCityNames = [
        'Houston' => 'TX',
        'Dallas' => 'TX',
        'Los Angeles' => 'CA',
        'New York' => 'NY',
        'Miami' => 'FL',
        'Chicago' => 'IL',
        'Phoenix' => 'AZ',
        'Atlanta' => 'GA',
        'Denver' => 'CO',
        'Seattle' => 'WA',
    ];

    public function handle(): int
    {
        $cityNames = $this->option('cities')
            ? array_map('trim', explode(',', $this->option('cities')))
            : $this->topCityNames;

        // If --cities is a flat list without state codes, treat as city names only
        $isFlatList = $this->option('cities') && !is_array($cityNames);

        $domainId = $this->option('domain');
        $domain = $domainId ? Domain::find($domainId) : (Domain::current() ?? Domain::first());

        if (!$domain) {
            $this->error('No domain found. Specify --domain=ID or switch to a domain in admin.');

            return self::FAILURE;
        }

        $this->info("Using domain: {$domain->name} ({$domain->domain})");
        $this->newLine();

        $found = [];
        $missing = [];

        if ($isFlatList || is_array($cityNames) && array_is_list($cityNames)) {
            // Flat list: just match by city name
            foreach ($cityNames as $name) {
                $city = City::where('name', $name)->with('state')->first();
                if ($city) {
                    $found[] = $city;
                } else {
                    $missing[] = $name;
                }
            }
        } else {
            // Associative array: match by name + state
            foreach ($cityNames as $name => $stateCode) {
                $city = City::where('name', $name)
                    ->whereHas('state', fn ($q) => $q->where('code', $stateCode))
                    ->with('state')
                    ->first();
                if ($city) {
                    $found[] = $city;
                } else {
                    $missing[] = "{$name}, {$stateCode}";
                }
            }
        }

        if (!empty($missing)) {
            $this->warn('Cities not found in database: ' . implode(', ', $missing));
        }

        if (empty($found)) {
            $this->error('No matching cities found in database.');

            return self::FAILURE;
        }

        $this->table(
            ['City', 'State', 'Service Types', 'Existing Pages'],
            array_map(fn (City $city) => [
                $city->name,
                $city->state?->code ?? '—',
                count($domain->getTopServiceTypes()),
                $city->servicePages()->where('domain_id', $domain->id)->count(),
            ], $found)
        );

        $serviceTypes = $domain->getTopServiceTypes();
        $totalJobs = count($found) * count($serviceTypes);

        $this->newLine();
        $this->info("Total: " . count($found) . " cities × " . count($serviceTypes) . " service types = {$totalJobs} page generations");

        if ($this->option('dry-run')) {
            $this->warn('Dry run — no jobs dispatched.');

            return self::SUCCESS;
        }

        if (!$this->confirm('Regenerate all service pages for these cities? This will overwrite existing content.')) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($found));
        $bar->start();

        $dispatched = 0;
        foreach ($found as $city) {
            GenerateCityContentJob::dispatch($city, $domain, $serviceTypes);
            $dispatched++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Dispatched {$dispatched} generation jobs ({$totalJobs} total page generations).");
        $this->line('Jobs run in the background. Monitor progress in Admin → Cities.');
        $this->line('Check storage/logs/city-page-generation.log for details.');

        return self::SUCCESS;
    }
}
