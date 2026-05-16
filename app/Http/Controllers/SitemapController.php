<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Domain;
use App\Models\NeighborhoodServicePage;
use App\Models\ServicePage;
use App\Models\State;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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
            $this->addNeighborhoodPages($sitemap, $domain);
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

            $heroImageUrl = $this->getHeroImageUrl();

            ServicePage::published()
                ->with('city.state')
                ->where('domain_id', $domain->id)
                ->lazy()
                ->take(50000)
                ->each(function ($page) use ($sitemap, $domain, $heroImageUrl) {
                    $priority = $this->calculatePagePriority($page, $domain);
                    $url = Url::create(url("/{$page->slug}"))
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency('weekly')
                        ->setPriority($priority);

                    if ($heroImageUrl) {
                        $url->addImage(
                            $heroImageUrl,
                            "{$page->service_type_label} porta potty rental - Potty Direct",
                            '',
                            "Porta potty rental in {$page->city->name}, {$page->city->state->code}"
                        );
                    }

                    $sitemap->add($url);
                });

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    protected function getHeroImageUrl(): ?string
    {
        $domain = $this->getDomain();
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        if ($prefix === 'localhost' || !Storage::disk('public')->exists($prefix . '/hero-banner-images')) {
            $prefix = 'pottydirect';
        }

        $images = Cache::remember("sitemap_hero_image_{$prefix}", 3600, function () use ($prefix) {
            return collect(Storage::disk('public')->files($prefix . '/hero-banner-images'))
                ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
                ->values()
                ->all();
        });

        $image = $images[0] ?? null;

        return $image ? url('storage/' . $image) : null;
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
                                ->setLastModificationDate(now())
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
            Cache::forget("sitemap_neighborhoods_{$domain->id}");
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
            '/complete-guide-to-porta-potty-rental' => ['priority' => 0.8, 'changefreq' => 'monthly'],
            '/units-calculator' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/wedding-porta-potty-rental' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/festival-portable-toilets' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/construction-site-porta-potty-rental' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/faq' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/osha-porta-potty-requirements' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/standard-vs-deluxe-vs-luxury-porta-potty' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/porta-potty-rental-cost' => ['priority' => 0.8, 'changefreq' => 'monthly'],
            '/porta-potty-rental-for-parties' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/emergency-porta-potty-rental' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/restroom-trailer-rental' => ['priority' => 0.7, 'changefreq' => 'monthly'],
            '/how-many-porta-potties-do-i-need' => ['priority' => 0.8, 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $path => $config) {
            $url = Url::create(url($path))
                ->setPriority($config['priority'])
                ->setChangeFrequency($config['changefreq']);

            $url->setLastModificationDate(now());

            $sitemap->add($url);
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
                            ->setLastModificationDate(now())
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
                            ->setLastModificationDate(now())
                            ->setPriority(0.5)
                            ->setChangeFrequency('weekly')
                    );
                }
            });
    }

    protected function addNeighborhoodPages(Sitemap $sitemap, Domain $domain): void
    {
        NeighborhoodServicePage::published()
            ->where('domain_id', $domain->id)
            ->chunk(500, function ($pages) use ($sitemap) {
                foreach ($pages as $page) {
                    $sitemap->add(
                        Url::create($page->url)
                            ->setLastModificationDate($page->updated_at)
                            ->setChangeFrequency('weekly')
                            ->setPriority(0.8)
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
