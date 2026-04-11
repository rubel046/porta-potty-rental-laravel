<?php

namespace App\Console\Commands;

use App\Models\AiApiKey;
use App\Models\Domain;
use App\Models\State;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;

class GenerateStateContentCommand extends Command
{
    protected $signature = 'content:generate:state {domain? : Domain slug}';

    protected $description = 'Generate content for states';

    public function __construct(
        protected ContentGeneratorService $contentGenerator
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $domainSlug = $this->argument('domain');
        $domain = $domainSlug ? Domain::where('slug', $domainSlug)->first() : null;

        $query = State::active();

        $states = $query->get();
        $this->info("Generating content for {$states->count()} states...");

        $bar = $this->output->createProgressBar($states->count());
        $bar->start();

        foreach ($states as $index => $state) {
            $this->generateForState($state, $domain);
            $bar->advance();

            // Rate limiting between states
            if ($index < $states->count() - 1) {
                $this->applyRateLimit();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done!');

        return Command::SUCCESS;
    }

    protected function applyRateLimit(): void
    {
        $keys = AiApiKey::active()->get();
        $totalRequests = $keys->sum('requests_today');

        // If high usage (>500 req/min), wait 10 seconds, else wait 3 seconds
        $waitTime = $totalRequests > 500 ? 10 : 3;

        $this->line("  Rate limiting - waiting {$waitTime}s (requests: {$totalRequests})");
        sleep($waitTime);
    }

    protected function generateForState(State $state, ?Domain $domain): void
    {
        $this->line("Generating content for {$state->name}...");

        try {
            $data = $this->contentGenerator->generateStatePageContent($state);

            $state->update([
                'h1_title' => $data['h1_title'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'content' => $data['content'],
                'word_count' => $data['word_count'],
            ]);

            $this->info("  ✓ {$state->name} - {$data['word_count']} words");
        } catch (\Throwable $e) {
            $this->error("  ✗ {$state->name} - {$e->getMessage()}");
        }
    }
}
