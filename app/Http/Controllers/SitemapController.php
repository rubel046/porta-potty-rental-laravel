<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
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

    public function index(): Response
    {
        $xml = Cache::remember('sitemap_main', now()->addHours(self::CACHE_TTL_HOURS), function () {
            $sitemap = Sitemap::create();

            $this->addStaticPages($sitemap);
            $this->addStates($sitemap);
            $this->addServicePages($sitemap);
            $this->addBlogPosts($sitemap);
            $this->addBlogCategories($sitemap);

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public function indexSitemaps(): Response
    {
        $xml = Cache::remember('sitemap_index', now()->addHours(self::CACHE_TTL_HOURS), function () {
            return SitemapIndex::create()
                ->add(url('/sitemap-full.xml'))
                ->add(url('/sitemap-cities.xml'))
                ->add(url('/sitemap-blog.xml'))
                ->render();
        });

        return $this->xmlResponse($xml);
    }

    public function cities(): Response
    {
        $xml = Cache::remember('sitemap_cities', now()->addHours(self::CACHE_TTL_HOURS), function () {
            $sitemap = Sitemap::create();

            ServicePage::published()
                ->where('service_type', 'general')
                ->chunk(500, function ($pages) use ($sitemap) {
                    foreach ($pages as $page) {
                        $sitemap->add(
                            Url::create(url("/{$page->slug}"))
                                ->setLastModificationDate($page->updated_at)
                                ->setChangeFrequency('weekly')
                                ->setPriority(0.8)
                        );
                    }
                });

            return $sitemap->render();
        });

        return $this->xmlResponse($xml);
    }

    public function blog(): Response
    {
        $xml = Cache::remember('sitemap_blog', now()->addHours(self::CACHE_TTL_HOURS), function () {
            $sitemap = Sitemap::create();

            BlogPost::published()->chunk(500, function ($posts) use ($sitemap) {
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
        Cache::forget('sitemap_main');
        Cache::forget('sitemap_cities');
        Cache::forget('sitemap_blog');
        Cache::forget('sitemap_index');
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

    protected function addStates(Sitemap $sitemap): void
    {
        State::active()->chunk(100, function ($states) use ($sitemap) {
            foreach ($states as $state) {
                $sitemap->add(
                    Url::create(url("/porta-potty-rental-{$state->slug}"))
                        ->setPriority(0.7)
                        ->setChangeFrequency('weekly')
                );
            }
        });
    }

    protected function addServicePages(Sitemap $sitemap): void
    {
        ServicePage::published()->chunk(500, function ($pages) use ($sitemap) {
            foreach ($pages as $page) {
                $priority = $this->calculatePagePriority($page);

                $sitemap->add(
                    Url::create(url("/{$page->slug}"))
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency('weekly')
                        ->setPriority($priority)
                );
            }
        });
    }

    protected function addBlogPosts(Sitemap $sitemap): void
    {
        BlogPost::published()->chunk(500, function ($posts) use ($sitemap) {
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

    protected function addBlogCategories(Sitemap $sitemap): void
    {
        BlogCategory::where('is_active', true)->chunk(100, function ($categories) use ($sitemap) {
            foreach ($categories as $category) {
                $sitemap->add(
                    Url::create(url("/blog/category/{$category->slug}"))
                        ->setPriority(0.5)
                        ->setChangeFrequency('weekly')
                );
            }
        });
    }

    protected function calculatePagePriority(ServicePage $page): float
    {
        $basePriority = 0.8;

        if ($page->service_type === ServicePage::TYPE_GENERAL) {
            $basePriority = 0.9;
        } elseif (in_array($page->service_type, [
            ServicePage::TYPE_WEDDING,
            ServicePage::TYPE_EVENT,
            ServicePage::TYPE_CONSTRUCTION,
        ])) {
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
