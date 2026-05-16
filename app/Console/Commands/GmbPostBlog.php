<?php

namespace App\Console\Commands;

use App\Models\GmbAccount;
use App\Services\GoogleBusinessProfileService;
use Illuminate\Console\Command;

class GmbPostBlog extends Command
{
    protected $signature = 'gmb:post-blog
        {--account= : Specific GMB account ID}
        {--limit=5 : Max blog posts to post per account}';

    protected $description = 'Post unpublished blog posts to Google Business Profile';

    public function handle(GoogleBusinessProfileService $gmb): int
    {
        if (!$gmb->isConfigured()) {
            $this->warn('GMB API is not configured. Skipping.');

            return self::SUCCESS;
        }

        $query = GmbAccount::where('is_active', true)
            ->where('auto_post', true);

        if ($accountId = $this->option('account')) {
            $query->where('id', $accountId);
        }

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->warn('No active GMB accounts with auto-post enabled.');

            return self::SUCCESS;
        }

        $limit = (int) $this->option('limit');
        $totalPosted = 0;

        foreach ($accounts as $account) {
            $this->info("Processing account: {$account->account_name}");

            $pendingPosts = $gmb->getPendingBlogPosts($account);
            $pendingPosts = array_slice($pendingPosts, 0, $limit);

            if (empty($pendingPosts)) {
                $this->line("  No pending blog posts for this account.");

                continue;
            }

            foreach ($pendingPosts as $post) {
                $blogPost = \App\Models\BlogPost::find($post['id']);
                if (!$blogPost) {
                    continue;
                }

                $this->line("  Posting: {$blogPost->title}");
                $result = $gmb->postBlogPost($account, $blogPost);

                if ($result['success']) {
                    $this->info("  ✓ Posted successfully");
                    $totalPosted++;
                } else {
                    $this->error("  ✗ Failed: {$result['error']}");
                }

                usleep(500000);
            }
        }

        $this->newLine();
        $this->info("Done! Posted {$totalPosted} blog posts to GBP.");

        return self::SUCCESS;
    }
}
