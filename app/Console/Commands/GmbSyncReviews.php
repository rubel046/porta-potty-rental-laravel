<?php

namespace App\Console\Commands;

use App\Models\GmbAccount;
use App\Services\GoogleBusinessProfileService;
use Illuminate\Console\Command;

class GmbSyncReviews extends Command
{
    protected $signature = 'gmb:sync-reviews
        {--account= : Specific GMB account ID}
        {--auto-reply : Force auto-reply even if disabled in settings}';

    protected $description = 'Fetch Google Business Profile reviews and auto-reply';

    public function handle(GoogleBusinessProfileService $gmb): int
    {
        if (!$gmb->isConfigured()) {
            $this->warn('GMB API is not configured. Skipping.');

            return self::SUCCESS;
        }

        $query = GmbAccount::where('is_active', true);

        if ($accountId = $this->option('account')) {
            $query->where('id', $accountId);
        }

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->warn('No active GMB accounts found.');

            return self::SUCCESS;
        }

        $totalReviews = 0;
        $totalReplied = 0;

        foreach ($accounts as $account) {
            $this->info("Processing account: {$account->account_name}");

            // Fetch reviews
            $this->line('  Fetching reviews...');
            $result = $gmb->fetchReviews($account);

            if ($result['success']) {
                $this->info("  ✓ Fetched {$result['total']} reviews ({$result['unread']} unread)");
                $totalReviews += $result['total'];
            } else {
                $this->error("  ✗ Failed: {$result['error']}");

                continue;
            }

            // Auto-reply if enabled or forced
            if ($account->auto_reply_reviews || $this->option('auto-reply')) {
                $this->line('  Auto-replying to unreviewed reviews...');
                $replyResult = $gmb->autoReplyToUnreviewed($account);

                if ($replyResult['success']) {
                    $this->info("  ✓ Replied to {$replyResult['replied']} reviews");
                    $totalReplied += $replyResult['replied'];
                } else {
                    $this->error("  ✗ Auto-reply failed: {$replyResult['error']}");
                }
            }
        }

        $this->newLine();
        $this->info("Done! Synced {$totalReviews} reviews, replied to {$totalReplied}.");

        return self::SUCCESS;
    }
}
