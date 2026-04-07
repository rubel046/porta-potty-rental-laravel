<?php

namespace App\Jobs;

use App\Http\Controllers\SitemapController;
use App\Models\State;
use App\Services\ContentGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateStateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        public State $state
    ) {}

    public function handle(ContentGeneratorService $generator): void
    {
        $cacheKey = "state_content_generation_{$this->state->id}";

        Cache::put("{$cacheKey}_status", 'processing', now()->addMinutes(30));
        Cache::put("{$cacheKey}_progress", 0, now()->addMinutes(30));
        Cache::put("{$cacheKey}_started_at", now()->toIso8601String(), now()->addMinutes(60));

        try {
            $data = $generator->generateStatePageContent($this->state);

            $this->state->update([
                'h1_title' => $data['h1_title'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'content' => $data['content'],
                'word_count' => $data['word_count'],
            ]);

            Cache::put("{$cacheKey}_status", 'completed', now()->addMinutes(30));
            Cache::put("{$cacheKey}_progress", 100, now()->addMinutes(30));

            SitemapController::invalidateCache();

            Log::info('State content generated', [
                'state' => $this->state->name,
                'word_count' => $data['word_count'],
            ]);
        } catch (\Throwable $e) {
            Cache::put("{$cacheKey}_status", 'failed', now()->addMinutes(30));
            Cache::put("{$cacheKey}_error", $e->getMessage(), now()->addMinutes(60));

            Log::error('State content generation failed', [
                'state' => $this->state->name,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $cacheKey = "state_content_generation_{$this->state->id}";
        Cache::put("{$cacheKey}_status", 'failed', now()->addMinutes(30));
        Cache::put("{$cacheKey}_error", $exception->getMessage(), now()->addMinutes(60));

        Log::error('State content generation failed', [
            'state' => $this->state->name,
            'error' => $exception->getMessage(),
        ]);
    }
}
