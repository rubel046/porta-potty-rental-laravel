<?php

namespace App\Jobs;

use App\Http\Controllers\SitemapController;
use App\Models\Domain;
use App\Models\DomainState;
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
        public State $state,
        public ?Domain $domain = null
    ) {}

    public function handle(ContentGeneratorService $generator): void
    {
        $domains = $this->domain ? collect([$this->domain]) : Domain::where('is_active', true)->get();

        foreach ($domains as $domain) {
            // Set the current domain for the generator service to use
            Domain::current($domain);

            $cacheKey = "state_content_generation_{$this->state->id}_{$domain->id}";

            Cache::put("{$cacheKey}_status", 'processing', now()->addMinutes(30));
            Cache::put("{$cacheKey}_progress", 0, now()->addMinutes(30));
            Cache::put("{$cacheKey}_started_at", now()->toIso8601String(), now()->addMinutes(60));

            try {
                $data = $generator->generateStatePageContent($this->state);

                DomainState::updateOrCreate(
                    ['domain_id' => $domain->id, 'state_id' => $this->state->id],
                    [
                        'h1_title' => $data['h1_title'],
                        'meta_title' => $data['meta_title'],
                        'meta_description' => $data['meta_description'],
                        'content' => $data['content'],
                        'word_count' => $data['word_count'],
                        'images' => $data['images'] ?? null,
                        'status' => true,
                    ]
                );

                Cache::put("{$cacheKey}_status", 'completed', now()->addMinutes(30));
                Cache::put("{$cacheKey}_progress", 100, now()->addMinutes(30));

                Log::info('State content generated', [
                    'state' => $this->state->name,
                    'domain' => $domain->name,
                    'word_count' => $data['word_count'],
                ]);

            } catch (\Throwable $e) {
                Cache::put("{$cacheKey}_status", 'failed', now()->addMinutes(30));
                Cache::put("{$cacheKey}_error", $e->getMessage(), now()->addMinutes(60));

                Log::error('State content generation failed', [
                    'state' => $this->state->name,
                    'domain' => $domain->name,
                    'error' => $e->getMessage(),
                ]);

                // Re-throw to let the queue handle the failure
                throw $e;
            }
        }

        // Invalidate sitemap cache after all domains are processed
        SitemapController::invalidateCache();
    }

    public function failed(\Throwable $exception): void
    {
        $domain = $this->domain ?? Domain::current();
        $cacheKey = "state_content_generation_{$this->state->id}_{$domain?->id}";
        Cache::put("{$cacheKey}_status", 'failed', now()->addMinutes(30));
        Cache::put("{$cacheKey}_error", $exception->getMessage(), now()->addMinutes(60));

        Log::error('State content generation failed', [
            'state' => $this->state->name,
            'error' => $exception->getMessage(),
        ]);
    }
}
