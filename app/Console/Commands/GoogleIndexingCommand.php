<?php

namespace App\Console\Commands;

use App\Models\IndexingUrl;
use App\Models\ServicePage;
use App\Services\GoogleIndexingService;
use Illuminate\Console\Command;

class GoogleIndexingCommand extends Command
{
    protected $signature = 'google:index
                            {--check : Check indexing status for pending URLs}
                            {--mark : Mark URLs as indexed (manual confirmation)}';

    protected $description = 'Submit URLs to Google Indexing API';

    public function __construct(
        protected GoogleIndexingService $indexingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (! $this->indexingService->isConfigured()) {
            $this->error('Google Indexing API not configured. Set GOOGLE_CLIENT_EMAIL and GOOGLE_PRIVATE_KEY in .env');
            $this->line('  - GOOGLE_CLIENT_EMAIL');
            $this->line('  - GOOGLE_PRIVATE_KEY');
            $this->line('  - GOOGLE_SEARCH_CONSOLE_URL');

            return Command::FAILURE;
        }

        if ($this->option('check')) {
            return $this->checkStatus();
        }

        if ($this->option('mark')) {
            return $this->markIndexed();
        }

        if (str_contains(config('app.url'), 'localhost')) {
            $this->error('Cannot index localhost URLs. Update APP_URL to a public domain in .env');
            $this->line('Current APP_URL: '.config('app.url'));

            return Command::FAILURE;
        }

        return $this->submitUrls();
    }

    protected function submitUrls(): int
    {
        $pendingCount = $this->indexingService->getPendingCount();

        $this->info("Pending URLs to index: {$pendingCount}");

        if ($pendingCount === 0) {
            $this->info('No URLs waiting for indexing.');

            return Command::SUCCESS;
        }

        $this->info('Submitting URLs to Google Indexing API...');

        $stats = $this->indexingService->processPendingUrls();

        $this->info("Submitted {$stats['total']} URLs:");
        $this->line("  - Service Pages: {$stats['service_pages']}");
        $this->line("  - State Pages: {$stats['domain_states']}");
        $this->line("  - Blog Posts: {$stats['blog_posts']}");

        if (! empty($stats['errors'])) {
            $this->warn('Errors encountered:');
            foreach (array_slice($stats['errors'], 0, 10) as $error) {
                $this->line("  - {$error}");
            }
            if (count($stats['errors']) > 10) {
                $this->line('  ... and '.(count($stats['errors']) - 10).' more errors');
            }
        }

        return Command::SUCCESS;
    }

    protected function checkStatus(): int
    {
        $this->info('Checking indexing status via Google Indexing API...');

        $submittedUrls = IndexingUrl::where('status', 'submitted')
            ->whereNull('indexed_at')
            ->limit(50)
            ->get();

        $checked = 0;
        $indexed = 0;

        foreach ($submittedUrls as $urlRecord) {
            $status = $this->indexingService->checkIndexedStatus($urlRecord->url);

            if ($status && ($status['indexed'] ?? false)) {
                $urlRecord->update([
                    'indexed' => true,
                    'indexed_at' => now(),
                    'status' => 'indexed',
                ]);
                $indexed++;
            } elseif (! $status) {
                // API call failed, keep as submitted
            }

            $checked++;
            $this->line("Checked {$checked}: {$urlRecord->url}");

            usleep(100000);
        }

        $this->info("Checked {$checked} URLs, {$indexed} now indexed.");

        return Command::SUCCESS;
    }

    protected function markIndexed(): int
    {
        $this->info('Manually marking URLs as indexed...');

        $threeDaysAgo = now()->subDays(3);

        $urls = ServicePage::where('generation_status', 'success')
            ->where('indexing_requested', true)
            ->whereNull('indexed_at')
            ->where('generated_at', '<', $threeDaysAgo)
            ->limit(50)
            ->get()
            ->map(fn ($p) => url($p->slug))
            ->toArray();

        if (! empty($urls)) {
            $count = $this->indexingService->markAsIndexed($urls);
            $this->info("Marked {$count} URLs as indexed.");
        }

        return Command::SUCCESS;
    }
}
