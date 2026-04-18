<?php

namespace App\Console\Commands;

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
            $this->error('Google Indexing API not configured. Please set:');
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

        return Command::SUCCESS;
    }

    protected function checkStatus(): int
    {
        $this->info('Checking indexing status via Google Indexing API...');

        $fourDaysAgo = now()->subDays(4);

        $pages = ServicePage::where('generation_status', 'success')
            ->where('indexing_requested', true)
            ->whereNull('indexed_at')
            ->limit(50)
            ->get();

        $checked = 0;
        $indexed = 0;

        foreach ($pages as $page) {
            $url = url($page->slug);
            $status = $this->indexingService->checkIndexedStatus($url);

            if ($status && ($status['indexed'] ?? false)) {
                $page->update(['indexed_at' => now()]);
                $indexed++;
            }

            $checked++;
            $this->line("Checked {$checked}: {$url}");

            usleep(100000);
        }

        $this->info("Checked {$checked} URLs, {$indexed} now indexed.");

        return Command::SUCCESS;
    }

    protected function markIndexed(): int
    {
        $this->info('Manually marking URLs as indexed...');

        $fourDaysAgo = now()->subDays(4);

        $urls = ServicePage::where('generation_status', 'success')
            ->where('indexing_requested', true)
            ->whereNull('indexed_at')
            ->where('generated_at', '<', $fourDaysAgo)
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
