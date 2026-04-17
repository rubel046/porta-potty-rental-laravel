<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateDailyBlogPost extends Command
{
    protected $signature = 'blog:generate-daily {--domain= : Specific domain ID to generate for}';

    protected $description = 'Generate 1 AI blog post for each active domain with random city and category';

    public function __construct(
        protected ContentGeneratorService $contentService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $domainId = $this->option('domain');

        $domains = Domain::where('is_active', true)
            ->when($domainId, fn ($q) => $q->where('id', $domainId))
            ->get();

        if ($domains->isEmpty()) {
            $this->info('No active domains found.');

            return Command::SUCCESS;
        }

        $this->info("Processing {$domains->count()} domain(s)...");

        foreach ($domains as $domain) {
            $this->generateForDomain($domain);
        }

        return Command::SUCCESS;
    }

    protected function generateForDomain(Domain $domain): void
    {
        $this->info("Processing domain: {$domain->domain}");

        try {
            // Set domain as current
            session(['current_domain_id' => $domain->id]);

            // Get random active city for this domain
            $city = $this->getRandomCity($domain);
            if (! $city) {
                $this->warn("  No active city found for domain {$domain->domain}");

                return;
            }

            // Get random category for this domain
            $category = $this->getRandomCategory($domain);
            if (! $category) {
                $this->warn("  No category found for domain {$domain->domain}");

                return;
            }

            $this->info("  Generating: {$category->name} - {$city->name}, {$city->state->code}");

            // Generate content
            $result = $this->contentService->generateBlogPostContent($category, $city);

            if (! $result || ! ($result['success'] ?? false)) {
                $this->error('  Failed to generate content: '.($result['error'] ?? 'Unknown error'));

                return;
            }

            // Create slug
            $slug = $result['slug'] ?? Str::slug($result['title'] ?? 'blog-post');

            // Make slug unique
            $baseSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            // Create blog post
            $post = BlogPost::create([
                'title' => $result['title'] ?? '',
                'slug' => $slug,
                'excerpt' => $result['excerpt'] ?? '',
                'content' => $result['content'] ?? '',
                'meta_title' => $result['meta_title'] ?? '',
                'meta_description' => $result['meta_description'] ?? '',
                'focus_keyword' => $result['focus_keyword'] ?? '',
                'featured_image' => $result['featured_image'] ?? '',
                'blog_category_id' => $category->id,
                'city_id' => $city->id,
                'domain_id' => $domain->id,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->info("  Created post: {$post->title} (ID: {$post->id})");

            // Sleep for 30 seconds before next domain post (avoid rate limiting)
            sleep(30);

            Log::info('Daily blog post generated', [
                'domain' => $domain->domain,
                'category' => $category->name,
                'city' => $city->name,
                'post_id' => $post->id,
            ]);

        } catch (\Exception $e) {
            $this->error('  Error: '.$e->getMessage());
            Log::error('Daily blog generation failed', [
                'domain' => $domain->domain,
                'error' => $e->getMessage(),
            ]);
        } finally {
            session()->forget('current_domain_id');
        }
    }

    protected function getRandomCity(Domain $domain): ?City
    {
        return City::whereHas('domainCities', function ($q) use ($domain) {
            $q->where('domain_id', $domain->id)->where('status', true);
        })
            ->where('is_active', true)
            ->with('state')
            ->inRandomOrder()
            ->first();
    }

    protected function getRandomCategory(Domain $domain): ?BlogCategory
    {
        return BlogCategory::where('domain_id', $domain->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();
    }
}
