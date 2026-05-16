<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Services\PageQualityService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class QualityScoreAll extends Command
{
    protected $signature = 'quality:score-all
        {--domain= : Specific domain ID}
        {--force : Re-score already scored pages}';

    protected $description = 'Score all service pages and persist results to page_quality_scores table';

    public function handle(PageQualityService $service): int
    {
        $domains = Domain::when($this->option('domain'), fn ($q) => $q->where('id', $this->option('domain')))
            ->get();

        if ($domains->isEmpty()) {
            $this->warn('No domains found.');

            return self::SUCCESS;
        }

        foreach ($domains as $domain) {
            $this->info("Scoring pages for domain: {$domain->domain}");

            $query = \App\Models\ServicePage::where('domain_id', $domain->id);

            if (! $this->option('force')) {
                $query->whereDoesntHave('qualityScore');
            }

            $total = $query->count();
            $this->line("  Pages to score: {$total}");

            if ($total === 0) {
                $this->line('  All already scored. Use --force to re-score.');

                continue;
            }

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $query->with('city.state')
                ->chunk(100, function ($pages) use ($service, $bar) {
                    foreach ($pages as $page) {
                        try {
                            $service->scoreAndPersist($page);
                        } catch (\Throwable $e) {
                            $this->error("\n  Failed page #{$page->id}: {$e->getMessage()}");
                        }
                        $bar->advance();
                    }
                });

            $bar->finish();
            $this->newLine();
            $this->info("  Done scoring {$domain->domain}");

            Cache::forget('quality:score-all:domain:' . $domain->id);
        }

        $this->newLine();
        $this->info('All domains scored successfully.');

        return self::SUCCESS;
    }
}
