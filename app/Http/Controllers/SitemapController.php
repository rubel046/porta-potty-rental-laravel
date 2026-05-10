<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Domain;
use App\Models\ServicePage;
use App\Models\State;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected const CACHE_TTL_HOURS = 6;

    protected function getDomain(): Domain
    {
        return Domain::current() ?? Domain::first();
    }

    protected function cacheKey(string $key): string
    {
        $domain = $this->getDomain();

        return "sitemap_{$key}_{$domain->id}";
    }

    public function index(): Response
    {
        $domain = $this->getDomain();
        $xml = Cache::remember($this->cacheKey('main'), now()->addHours(self::CACHE_TTL_HOURS), function () use ($domain) {
            $sitemap = Sitemap::create();

            $this->addStaticPages($sitemap);
            $this->addStates($sitemap, $domain);
            $this->addServicePages($sitemap, $domain);
            $this->addBlogPosts($sitemap, $domain);
            $this->addBlogCategories($sitemap, $domain);

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public function indexSitemaps(): Response
    {
        $domain = $this->getDomain();
        $xml = Cache::remember($this->cacheKey('index'), now()->addHours(self::CACHE_TTL_HOURS), function () {
            return SitemapIndex::create()
                ->add(url('/sitemap-full.xml'))
                ->add(url('/sitemap-cities.xml'))
                ->add(url('/sitemap-states.xml'))
                ->add(url('/sitemap-blog.xml'))
                ->render();
        });

        return $this->xmlResponse($xml);
    }

    public function cities(): Response
    {
        $domain = $this->getDomain();
        $xml = Cache::remember($this->cacheKey('cities'), now()->addHours(self::CACHE_TTL_HOURS), function () use ($domain) {
            $sitemap = Sitemap::create();

            ServicePage::published()
                ->where('domain_id', $domain->id)
                ->chunk(500, function ($pages) use ($sitemap, $domain) {
                    foreach ($pages as $page) {
                        $priority = $this->calculatePagePriority($page, $domain);
                        $sitemap->add(
                            Url::create(url("/{$page->slug}"))
                                ->setLastModificationDate($page->updated_at)
                                ->setChangeFrequency('weekly')
                                ->setPriority($priority)
                        );
                    }
                });

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public function states(): Response
    {
        $domain = $this->getDomain();
        $xml = Cache::remember($this->cacheKey('states'), now()->addHours(self::CACHE_TTL_HOURS), function () use ($domain) {
            $sitemap = Sitemap::create();
            $slugPrefix = $domain->getServiceSlugPrefix();

            State::whereHas('domainStates', fn ($q) => $q->where('domain_id', $domain->id)->where('status', true))
                ->chunk(100, function ($states) use ($sitemap, $slugPrefix) {
                    foreach ($states as $state) {
                        $sitemap->add(
                            Url::create(url("/{$slugPrefix}-rental-{$state->slug}"))
                                ->setPriority(0.7)
                                ->setChangeFrequency('weekly')
                        );
                    }
                });

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public function blog(): Response
    {
        $domain = $this->getDomain();
        $xml = Cache::remember($this->cacheKey('blog'), now()->addHours(self::CACHE_TTL_HOURS), function () use ($domain) {
            $sitemap = Sitemap::create();

            BlogPost::published()
                ->where('domain_id', $domain->id)
                ->chunk(500, function ($posts) use ($sitemap) {
                    foreach ($posts as $post) {
                        $sitemap->add(
                            Url::create(url("/blog/{$post->slug}"))
                                ->setLastModificationDate($post->updated_at)
                                ->setChangeFrequency('monthly')
                                ->setPriority(0.6)
                        );
                    }
                });

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public static function invalidateCache(): void
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $prefix = "sitemap_main_{$domain->id}";
            Cache::forget($prefix);
            Cache::forget("sitemap_cities_{$domain->id}");
            Cache::forget("sitemap_states_{$domain->id}");
            Cache::forget("sitemap_blog_{$domain->id}");
            Cache::forget("sitemap_index_{$domain->id}");
        }
    }

    protected function addStaticPages(Sitemap $sitemap): void
    {
        $staticPages = [
            '/' => ['priority' => 1.0, 'changefreq' => 'daily'],
            '/services' => ['priority' => 0.9, 'changefreq' => 'weekly'],
            '/pricing' => ['priority' => 0.8, 'changefreq' => 'monthly'],
            '/locations' => ['priority' => 0.9, 'changefreq' => 'weekly'],
            '/about' => ['priority' => 0.6, 'changefreq' => 'monthly'],
            '/blog' => ['priority' => 0.8, 'changefreq' => 'daily'],
            '/privacy-policy' => ['priority' => 0.3, 'changefreq' => 'yearly'],
            '/terms-of-service' => ['priority' => 0.3, 'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $path => $config) {
            $sitemap->add(
                Url::create(url($path))
                    ->setPriority($config['priority'])
                    ->setChangeFrequency($config['changefreq'])
            );
        }
    }

    protected function addStates(Sitemap $sitemap, Domain $domain): void
    {
        $slugPrefix = $domain->getServiceSlugPrefix();

        State::whereHas('domainStates', fn ($q) => $q->where('domain_id', $domain->id)->where('status', true))
            ->chunk(100, function ($states) use ($sitemap, $slugPrefix) {
                foreach ($states as $state) {
                    $sitemap->add(
                        Url::create(url("/{$slugPrefix}-rental-{$state->slug}"))
                            ->setPriority(0.7)
                            ->setChangeFrequency('weekly')
                    );
                }
            });
    }

    protected function addServicePages(Sitemap $sitemap, Domain $domain): void
    {
        ServicePage::published()
            ->where('domain_id', $domain->id)
            ->chunk(500, function ($pages) use ($sitemap, $domain) {
                foreach ($pages as $page) {
                    $priority = $this->calculatePagePriority($page, $domain);

                    $sitemap->add(
                        Url::create(url("/{$page->slug}"))
                            ->setLastModificationDate($page->updated_at)
                            ->setChangeFrequency('weekly')
                            ->setPriority($priority)
                    );
                }
            });
    }

    protected function addBlogPosts(Sitemap $sitemap, Domain $domain): void
    {
        BlogPost::published()
            ->where('domain_id', $domain->id)
            ->chunk(500, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $sitemap->add(
                        Url::create(url("/blog/{$post->slug}"))
                            ->setLastModificationDate($post->updated_at)
                            ->setChangeFrequency('monthly')
                            ->setPriority(0.6)
                    );
                }
            });
    }

    protected function addBlogCategories(Sitemap $sitemap, Domain $domain): void
    {
        BlogCategory::where('domain_id', $domain->id)
            ->where('is_active', true)
            ->chunk(100, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $sitemap->add(
                        Url::create(url("/blog/category/{$category->slug}"))
                            ->setPriority(0.5)
                            ->setChangeFrequency('weekly')
                    );
                }
            });
    }

    protected function calculatePagePriority(ServicePage $page, ?Domain $domain = null): float
    {
        $basePriority = 0.8;
        $domain ??= $this->getDomain();
        $serviceTypes = $domain->getServiceTypes();

        if (! empty($serviceTypes) && $page->service_type === $serviceTypes[0]) {
            $basePriority = 0.9;
        } elseif (in_array($page->service_type, array_slice($serviceTypes, 1, 3))) {
            $basePriority = 0.85;
        }

        if ($page->seo_score && $page->seo_score >= 80) {
            $basePriority += 0.05;
        }

        return min($basePriority, 1.0);
    }

    protected function xmlResponse(string $xml): Response
    {
        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age='.(self::CACHE_TTL_HOURS * 3600),
        ]);
    }
}
