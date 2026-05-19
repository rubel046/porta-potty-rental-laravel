<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Domain;
use App\Models\Keyword;
use Illuminate\Console\Command;

class GenerateDomainKeywords extends Command
{
    protected $signature = 'keywords:generate
        {--domain= : Specific domain ID}
        {--force : Regenerate existing city-specific keywords}
        {--dry-run : Show what would be generated without inserting}
        {--city-limit=1000 : Max cities to process for geo variants (0 = all)}';

    protected $description = 'Generate city-specific keyword variants from domain secondary_keywords for every linked city';

    public function handle(): int
    {
        $domains = $this->option('domain')
            ? Domain::where('id', $this->option('domain'))->get()
            : Domain::where('is_active', true)->get();

        if ($domains->isEmpty()) {
            $this->error('No active domains found.');

            return self::FAILURE;
        }

        $totalInserted = 0;
        $totalSkipped = 0;

        foreach ($domains as $domain) {
            $this->newLine();
            $this->info("Processing domain: {$domain->name} ({$domain->domain})");

            $secondaryKeywords = $domain->getSecondaryKeywords();

            if (empty($secondaryKeywords)) {
                $this->warn('  No secondary keywords configured.');

                continue;
            }

            [$geoTemplates, $nonGeokeywords] = collect($secondaryKeywords)
                ->partition(fn ($kw) => str_contains($kw, '[city]') || str_contains($kw, '[state]') || str_contains($kw, '[county]'));

            $nonGeoCount = $this->generateNonGeoKeywords($domain, $nonGeokeywords->values()->all());
            $totalInserted += $nonGeoCount;
            $this->line("  Non-geo keywords: {$nonGeoCount} generated");

            $cityIds = $domain->domainCities()
                ->where('status', true)
                ->pluck('city_id');

            if ($cityIds->isEmpty()) {
                $this->warn('  No active cities linked. Skipping geo variants.');

                continue;
            }

            $cityLimit = (int) $this->option('city-limit');

            // Only process cities that don't already have geo keyword variants
            $existingKeywords = Keyword::where('domain_id', $domain->id)
                ->whereNotNull('volume')
                ->pluck('keyword');

            $cities = City::whereIn('id', $cityIds)
                ->with('state')
                ->orderByDesc('population')
                ->when($cityLimit > 0, fn ($q) => $q->limit($cityLimit))
                ->get();

            $cities = $this->option('force')
                ? $cities
                : $cities->filter(function ($city) use ($geoTemplates, $existingKeywords) {
                    foreach ($geoTemplates as $template) {
                        $resolved = $this->resolveGeoPlaceholders($template, $city);
                        if (! $existingKeywords->contains($resolved)) {
                            return true; // At least one keyword missing — process this city
                        }
                    }

                    return false; // All variants already exist — skip
                });

            $linkCount = $cities->count() * $geoTemplates->count();
            $bar = $this->output->createProgressBar($linkCount);
            $bar->start();

            $domainGeoCount = 0;
            $domainSkipCount = 0;

            foreach ($cities as $city) {
                foreach ($geoTemplates as $template) {
                    $resolved = $this->resolveGeoPlaceholders($template, $city);
                    $estimatedVolume = $this->estimateVolume($city, $template);

                    if (! $this->option('force')) {
                        $exists = Keyword::where('domain_id', $domain->id)
                            ->where('keyword', $resolved)
                            ->exists();

                        if ($exists) {
                            $domainSkipCount++;
                            $bar->advance();

                            continue;
                        }
                    }

                    if ($this->option('dry-run')) {
                        $domainGeoCount++;
                        $bar->advance();

                        continue;
                    }

                    Keyword::updateOrCreate(
                        ['domain_id' => $domain->id, 'keyword' => $resolved],
                        [
                            'volume' => $estimatedVolume,
                            'competition' => 'low',
                            'cpc' => null,
                            'service_type' => $this->inferServiceType($template),
                            'tier' => $estimatedVolume >= 200 ? 1 : ($estimatedVolume >= 50 ? 2 : 3),
                            'is_active' => true,
                        ]
                    );

                    $domainGeoCount++;
                    $bar->advance();
                }
            }

            $bar->finish();
            $this->newLine();
            $this->line("  Geo keyword variants: {$domainGeoCount} added, {$domainSkipCount} skipped");
            $totalInserted += $domainGeoCount;
            $totalSkipped += $domainSkipCount;
        }

        $this->newLine();
        $this->info("Done. Total keywords generated: {$totalInserted}, skipped: {$totalSkipped}");

        return self::SUCCESS;
    }

    protected function generateNonGeoKeywords(Domain $domain, array $keywords): int
    {
        $count = 0;

        foreach ($keywords as $keyword) {
            $exists = Keyword::where('domain_id', $domain->id)
                ->where('keyword', $keyword)
                ->exists();

            if ($exists && ! $this->option('force')) {
                continue;
            }

            if ($this->option('dry-run')) {
                $count++;

                continue;
            }

            Keyword::updateOrCreate(
                ['domain_id' => $domain->id, 'keyword' => $keyword],
                [
                    'volume' => null,
                    'competition' => 'low',
                    'cpc' => null,
                    'service_type' => $this->inferServiceType($keyword),
                    'tier' => 2,
                    'is_active' => true,
                ]
            );

            $count++;
        }

        return $count;
    }

    protected function resolveGeoPlaceholders(string $keyword, City $city): string
    {
        $replacements = [
            '[city]' => strtolower($city->name),
            '[CITY]' => $city->name,
            '[state]' => strtolower($city->state?->code ?? ''),
            '[STATE]' => $city->state?->code ?? '',
            '[state_name]' => $city->state?->name ?? '',
            '[county]' => strtolower($city->county ?? $city->name),
            '[COUNTY]' => $city->county ?? $city->name,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $keyword);
    }

    protected function estimateVolume(City $city, string $template): ?int
    {
        $pop = $city->population ?? 0;

        if ($pop >= 1000000) {
            $base = 300;
        } elseif ($pop >= 500000) {
            $base = 200;
        } elseif ($pop >= 100000) {
            $base = 100;
        } elseif ($pop >= 50000) {
            $base = 50;
        } elseif ($pop >= 10000) {
            $base = 20;
        } else {
            $base = 10;
        }

        $isNiche = str_contains($template, 'dumpster') || str_contains($template, 'septic') || str_contains($template, 'fence');

        return $isNiche ? max(10, (int) ($base * 0.3)) : $base;
    }

    protected function inferServiceType(string $keyword): ?string
    {
        $map = [
            'construction' => 'construction',
            'wedding' => 'wedding',
            'event' => 'event',
            'party' => 'party',
            'emergency' => 'emergency',
            'luxury' => 'luxury',
            'deluxe' => 'deluxe',
            'ada' => 'ada',
            'handicap' => 'ada',
            'accessible' => 'ada',
            'residential' => 'residential',
            'shower' => 'shower',
            'dumpster' => 'dumpster',
            'septic' => 'septic',
            'sanitizer' => 'sanitizer',
            'hand wash' => 'sanitizer',
            'handwash' => 'handwash-trailer',
            'urinal' => 'portable-urinal',
            'fence' => 'temporary-fencing',
            'holding' => 'holding',
            'high rise' => 'highrise',
            'standard' => 'standard',
            'plumber' => null,
            'plumbing' => null,
            'drain' => null,
            'water heater' => null,
            'pipe' => null,
            'leak' => null,
        ];

        $lower = strtolower($keyword);

        foreach ($map as $pattern => $type) {
            if (str_contains($lower, $pattern)) {
                return $type;
            }
        }

        return null;
    }
}
