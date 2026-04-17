<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Domain;
use App\Services\ContentGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateCityContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;

    public int $timeout = 1800;

    protected int $retryDelaySeconds = 10;

    protected int $maxRetriesPerType = 3;

    public function __construct(
        public City $city,
        public ?Domain $domain = null,
        public array $types = []
    ) {
        if (empty($this->types) && $this->domain) {
            $this->types = $this->domain->getServiceTypes();
        }
        if (empty($this->types)) {
            $this->types = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential', 'portable'];
        }
    }

    public function handle(ContentGeneratorService $generator): void
    {
        $total = count($this->types);
        $cacheKey = "city_content_generation_{$this->city->id}";
        $errors = [];
        $domainId = $this->domain?->id;

        // Mark all service pages as processing
        $this->city->servicePages()
            ->where('domain_id', $domainId)
            ->update(['generation_status' => 'processing']);

        foreach ($this->types as $index => $type) {
            $progress = round((($index + 1) / $total) * 100);
            Cache::put("{$cacheKey}_progress", $progress, now()->addMinutes(30));
            Cache::put("{$cacheKey}_current_type", $type, now()->addMinutes(30));

            $typeSuccess = false;
            $typeRetries = 0;

            while (! $typeSuccess && $typeRetries < $this->maxRetriesPerType) {
                try {
                    $data = $generator->generateServicePageContent($this->city, $type);

                    $domainId = $this->domain?->id;

                    $this->city->servicePages()->updateOrCreate(
                        ['slug' => $data['slug'], 'domain_id' => $domainId],
                        [
                            'domain_id' => $domainId,
                            'service_type' => $data['service_type'] ?? $type,
                            'h1_title' => $data['h1_title'] ?? '',
                            'meta_title' => $data['meta_title'] ?? '',
                            'meta_description' => $data['meta_description'] ?? '',
                            'content' => $data['content'] ?? '',
                            'images' => $data['images'] ?? null,
                            'word_count' => $data['word_count'] ?? 0,
                            'is_published' => true,
                            'published_at' => now(),
                            'generation_status' => 'success',
                            'generated_at' => now(),
                        ]
                    );

                    if (! empty($data['faqs'])) {
                        foreach ($data['faqs'] as $i => $faq) {
                            $this->city->faqs()->updateOrCreate(
                                ['question' => $faq['question'], 'service_type' => $type],
                                array_merge($faq, ['service_type' => $type, 'sort_order' => $i, 'is_active' => true])
                            );
                        }
                    }

                    if (! empty($data['testimonials'])) {
                        foreach ($data['testimonials'] as $t) {
                            $this->city->testimonials()->updateOrCreate(
                                ['customer_name' => $t['customer_name'], 'service_type' => $type],
                                array_merge($t, ['service_type' => $type, 'is_active' => true])
                            );
                        }
                    }

                    $typeSuccess = true;

                    // Sleep for 15 sec before next service type (avoid rate limiting)
                    sleep(15);
                } catch (\Throwable $e) {
                    $typeRetries++;

                    if ($this->isApiKeyExhausted($e)) {
                        Log::warning("API keys exhausted, waiting {$this->retryDelaySeconds}s before retry", [
                            'city' => $this->city->name,
                            'type' => $type,
                            'retry' => $typeRetries,
                        ]);
                        sleep($this->retryDelaySeconds);
                    } else {
                        $errors[] = "{$type}: {$e->getMessage()}";
                        Log::error("Failed to generate {$type} content", [
                            'city' => $this->city->name,
                            'type' => $type,
                            'error' => $e->getMessage(),
                        ]);
                        // Continue to next type instead of breaking
                    }
                }
            }

            if (! $typeSuccess && $typeRetries >= $this->maxRetriesPerType) {
                $errors[] = "{$type}: Max retries reached";

                $domainId = $this->domain?->id;
                $this->city->servicePages()->updateOrCreate(
                    ['slug' => "{$type}-rental-{$this->city->slug}", 'domain_id' => $domainId],
                    [
                        'domain_id' => $domainId,
                        'service_type' => $type,
                        'generation_status' => 'failed',
                        'generation_error' => "Max retries reached after {$this->maxRetriesPerType} attempts",
                    ]
                );
            }

            // No extra sleep - already sleeping after success
            sleep(30);
        }

        $this->city->servicePages()
            ->where('domain_id', $domainId)
            ->update([
                'generation_status' => 'success',
                'generated_at' => now(),
            ]);

        $domainId = $this->domain?->id;

        if (! empty($errors)) {
            Cache::put("{$cacheKey}_errors", $errors, now()->addMinutes(60));
            Log::warning('Content generation completed with errors', ['city' => $this->city->name, 'errors' => $errors]);

            $this->city->servicePages()
                ->where('domain_id', $domainId)
                ->whereNull('generated_at')
                ->update(['generation_status' => 'failed', 'generation_error' => 'Partial generation - some types failed']);
        } else {
            $this->city->servicePages()
                ->where('domain_id', $domainId)
                ->whereNull('generated_at')
                ->update(['generation_status' => 'success', 'generated_at' => now()]);
        }

        Cache::forget("{$cacheKey}_current_type");
        Cache::put("{$cacheKey}_status", empty($errors) ? 'completed' : 'partial', now()->addMinutes(30));
        Cache::forget("{$cacheKey}_progress");

        Log::info('Content generation completed', ['city' => $this->city->name, 'errors' => count($errors)]);

        if (empty($errors)) {
            $this->city->update(['is_active' => true]);
        }
    }

    protected function isApiKeyExhausted(\Throwable $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'ai generation failed')
            || str_contains($message, 'no active api keys')
            || str_contains($message, 'all api keys failed')
            || str_contains($message, 'all api keys exhausted');
    }

    public function failed(\Throwable $exception): void
    {
        $cacheKey = "city_content_generation_{$this->city->id}";
        Cache::put("{$cacheKey}_status", 'failed', now()->addMinutes(30));

        Log::error('Content generation failed', [
            'city' => $this->city->name,
            'error' => $exception->getMessage(),
        ]);
    }
}
