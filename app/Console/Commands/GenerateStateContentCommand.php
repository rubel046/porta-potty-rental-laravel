<?php

namespace App\Console\Commands;

use App\Models\State;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;

class GenerateStateContentCommand extends Command
{
    protected $signature = 'state:generate-content {--state= : Specific state code} {--all : Generate content for all states}';

    protected $description = 'Generate SEO content for state landing pages';

    public function __construct(
        protected ContentGeneratorService $contentGenerator
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $stateCode = $this->option('state');
        $all = $this->option('all');

        if (! $stateCode && ! $all) {
            $this->error('Please specify --state=XX or --all');

            return Command::FAILURE;
        }

        if ($stateCode) {
            $state = State::where('code', strtoupper($stateCode))->first();

            if (! $state) {
                $this->error("State '{$stateCode}' not found.");

                return Command::FAILURE;
            }

            $this->generateForState($state);

            return Command::SUCCESS;
        }

        $states = State::active()->get();
        $this->info("Generating content for {$states->count()} states...");

        $bar = $this->output->createProgressBar($states->count());
        $bar->start();

        foreach ($states as $index => $state) {
            $this->generateForState($state);
            $bar->advance();

            if ($index < $states->count() - 1) {
                $this->line('  Waiting 2 minutes before next state...');
                sleep(120);
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done!');

        return Command::SUCCESS;
    }

    protected function generateForState(State $state): void
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
