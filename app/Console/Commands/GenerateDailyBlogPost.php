<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Services\ContentGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateDailyBlogPost extends Command
{
    protected $signature = 'blog:generate-daily {--domain= : Specific domain ID to generate for} {--type= : "pillar" or "cluster" (default: auto)}';

    protected $description = 'Generate 1 AI blog post per active domain — pillar (every 7th) or cluster post';

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
            session(['current_domain_id' => $domain->id]);

            $isPillar = $this->option('type') === 'pillar'
                || ($this->option('type') !== 'cluster' && $this->shouldGeneratePillar($domain));

            if ($isPillar) {
                $this->generatePillarPost($domain);
            } else {
                $this->generateClusterPost($domain);
            }
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

    protected function generatePillarPost(Domain $domain): void
    {
        $category = $this->getLeastUsedCategory($domain);
        if (! $category) {
            $this->warn("  No active category for domain {$domain->domain}");

            return;
        }

        $this->info("  Generating PILLAR post for category: {$category->name}");

        $result = $this->contentService->generateBlogPostContent($category, null, 1, true);

        if (! $result || ! ($result['success'] ?? false)) {
            $this->error('  Failed to generate pillar: '.($result['error'] ?? 'Unknown'));

            return;
        }

        $slug = $result['slug'] ?? Str::slug($result['title'] ?? 'pillar-post');
        $baseSlug = $slug;
        $counter = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $autoPublish = filter_var(env('BLOG_AUTO_PUBLISH', false), FILTER_VALIDATE_BOOLEAN);

        $post = BlogPost::create([
            'title' => $result['title'] ?? '',
            'slug' => $slug,
            'excerpt' => $result['excerpt'] ?? '',
            'content' => $result['content'] ?? '',
            'content_html' => $result['content'] ?? null,
            'meta_title' => $result['meta_title'] ?? '',
            'meta_description' => $result['meta_description'] ?? '',
            'focus_keyword' => $result['focus_keyword'] ?? '',
            'featured_image' => $result['featured_image'] ?? '',
            'blog_category_id' => $category->id,
            'city_id' => null,
            'domain_id' => $domain->id,
            'is_pillar' => true,
            'is_published' => $autoPublish,
            'published_at' => $autoPublish ? now() : null,
        ]);

        $this->info("  Created PILLAR post #{$post->id}: {$post->title}");

        Cache::increment("pillar_counter_{$domain->id}");
        sleep(30);
    }

    protected function generateClusterPost(Domain $domain): void
    {
        $city = $this->getRandomCity($domain);
        if (! $city) {
            $this->warn("  No active city for domain {$domain->domain}");

            return;
        }

        $category = $this->getRandomCategory($domain);
        if (! $category) {
            $this->warn("  No active category for domain {$domain->domain}");

            return;
        }

        // Find latest pillar for this category to link to
        $pillar = BlogPost::where('domain_id', $domain->id)
            ->where('blog_category_id', $category->id)
            ->where('is_pillar', true)
            ->latest('id')
            ->first();

        $iteration = $this->getCategoryIteration($domain, $category);
        $this->info("  Generating CLUSTER #{$iteration}: {$category->name} - {$city->name}, {$city->state->code}");

        $result = $this->contentService->generateBlogPostContent($category, $city, $iteration, false, $pillar?->id);

        if (! $result || ! ($result['success'] ?? false)) {
            $this->error('  Failed to generate cluster: '.($result['error'] ?? 'Unknown'));

            return;
        }

        // Inject pillar link into cluster content if a pillar exists
        if ($pillar) {
            $pillarLink = "<a href=\"{$pillar->url}\" class=\"text-blue-600 font-semibold hover:underline\">" . e($pillar->title) . "</a>";
            $result['content'] = str_replace('{{PILLAR_LINK}}', $pillarLink, $result['content']);
        }

        $slug = $result['slug'] ?? Str::slug($result['title'] ?? 'cluster-post');
        $baseSlug = $slug;
        $counter = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $autoPublish = filter_var(env('BLOG_AUTO_PUBLISH', false), FILTER_VALIDATE_BOOLEAN);

        $post = BlogPost::create([
            'title' => $result['title'] ?? '',
            'slug' => $slug,
            'excerpt' => $result['excerpt'] ?? '',
            'content' => $result['content'] ?? '',
            'content_html' => $result['content'] ?? null,
            'meta_title' => $result['meta_title'] ?? '',
            'meta_description' => $result['meta_description'] ?? '',
            'focus_keyword' => $result['focus_keyword'] ?? '',
            'featured_image' => $result['featured_image'] ?? '',
            'blog_category_id' => $category->id,
            'city_id' => $city->id,
            'domain_id' => $domain->id,
            'pillar_id' => $pillar?->id,
            'is_published' => $autoPublish,
            'published_at' => $autoPublish ? now() : null,
        ]);

        Cache::increment("cluster_counter_{$domain->id}_{$category->id}");

        $this->info(sprintf(
            '  Created CLUSTER post #%d: %s (pillar: %s)',
            $post->id, $post->title, $pillar ? "#{$pillar->id}" : 'none'
        ));

        sleep(30);

        Log::info('Daily cluster blog post generated', [
            'domain' => $domain->domain,
            'category' => $category->name,
            'city' => $city->name,
            'post_id' => $post->id,
            'pillar_id' => $pillar?->id,
        ]);
    }

    protected function shouldGeneratePillar(Domain $domain): bool
    {
        $counter = (int) Cache::get("pillar_counter_{$domain->id}", 0);
        $totalPosts = BlogPost::where('domain_id', $domain->id)->count();

        // Generate pillar as first post, then every 7 posts
        if ($totalPosts === 0) {
            return true;
        }

        return $counter === 0 || ($totalPosts % 7 === 0);
    }

    protected function getCategoryIteration(Domain $domain, BlogCategory $category): int
    {
        return (int) Cache::get("cluster_counter_{$domain->id}_{$category->id}", 0) + 1;
    }

    protected function getLeastUsedCategory(Domain $domain): ?BlogCategory
    {
        return BlogCategory::where('domain_id', $domain->id)
            ->where('is_active', true)
            ->withCount(['posts' => fn ($q) => $q->where('is_pillar', true)])
            ->orderBy('posts_count')
            ->first();
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
