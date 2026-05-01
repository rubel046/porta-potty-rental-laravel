<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RegenerateBlogExcerpts extends Command
{
    protected $signature = 'blog:regenerate-excerpts {--post-id= : Specific post ID to regenerate} {--force : Force regenerate even if excerpt exists}';

    protected $description = 'Regenerate SEO-friendly excerpts for blog posts using AI';

    public function handle(ContentGeneratorService $contentService): int
    {
        $postId = $this->option('post-id');
        $force = $this->option('force');

        $query = BlogPost::published()->with('category', 'city');

        if ($postId) {
            $query->where('id', $postId);
        } elseif (! $force) {
            $query->where(function ($q) {
                $q->whereNull('excerpt')
                    ->orWhere('excerpt', '')
                    ->orWhereRaw('LENGTH(excerpt) < 200');
            });
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            $this->info('No posts found to process.');

            return self::SUCCESS;
        }

        $this->info("Found {$posts->count()} post(s) to process.");
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        $successCount = 0;
        $errorCount = 0;

        foreach ($posts as $post) {
            try {
                $excerpt = $contentService->generateExcerpt($post);

                if ($excerpt) {
                    $post->update(['excerpt' => $excerpt]);
                    $successCount++;
                } else {
                    $this->warn("\nFailed to generate excerpt for post ID: {$post->id}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                Log::error('Excerpt regeneration failed', [
                    'post_id' => $post->id,
                    'error' => $e->getMessage(),
                ]);
                $this->warn("\nError processing post ID: {$post->id} - {$e->getMessage()}");
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Successfully regenerated: {$successCount}");
        if ($errorCount > 0) {
            $this->warn("✗ Failed: {$errorCount}");
        }

        return self::SUCCESS;
    }
}
