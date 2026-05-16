<?php

namespace App\Console\Commands;

use App\Jobs\GenerateNeighborhoodContentJob;
use App\Models\Domain;
use App\Models\Neighborhood;
use Illuminate\Console\Command;

class GenerateNeighborhoodContent extends Command
{
    protected $signature = 'neighborhoods:generate-content
        {--limit=20 : Number of neighborhoods to process}
        {--domain= : Domain ID to generate for}
        {--type= : Single service type to generate (defaults to all domain types)}
        {--force : Regenerate existing content}';

    protected $description = 'Generate AI service page content for neighborhoods without published pages';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $force = (bool) $this->option('force');
        $domain = $this->option('domain')
            ? Domain::findOrFail($this->option('domain'))
            : (Domain::current() ?? Domain::first());

        if (!$domain) {
            $this->error('No domain found. Create a domain first.');

            return Command::FAILURE;
        }

        $types = $this->option('type')
            ? [$this->option('type')]
            : $domain->getServiceTypes();

        $query = Neighborhood::with('city.state')
            ->where('is_active', true);

        if (!$force) {
            $query->whereDoesntHave('servicePages', function ($q) use ($domain) {
                $q->where('domain_id', $domain->id)->where('is_published', true);
            });
        }

        $neighborhoods = $query->limit($limit)->get();

        if ($neighborhoods->isEmpty()) {
            $this->info('No pending neighborhoods found.');

            return Command::SUCCESS;
        }

        $this->info("Dispatching content generation for {$neighborhoods->count()} neighborhoods...");

        $bar = $this->output->createProgressBar($neighborhoods->count());
        $bar->start();

        foreach ($neighborhoods as $nb) {
            GenerateNeighborhoodContentJob::dispatchSync($nb, $domain, $types);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done. All neighborhood content generated.');

        // Bust sitemap cache
        \App\Http\Controllers\SitemapController::invalidateCache();
        $this->info('Sitemap cache busted.');

        return Command::SUCCESS;
    }
}
