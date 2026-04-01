<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\ServicePage;
use App\Models\State;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create();

        $sitemap->add(url('/'));
        $sitemap->add(url('/locations'));
        $sitemap->add(url('/about'));
        $sitemap->add(url('/privacy-policy'));
        $sitemap->add(url('/terms-of-service'));
        $sitemap->add(url('/blog'));

        State::active()->each(function (State $state) use ($sitemap) {
            $sitemap->add(url("/porta-potty-rental-{$state->slug}"));
        });

        ServicePage::published()->with('city')->each(function (ServicePage $page) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/{$page->slug}"))
                    ->setLastModificationDate($page->updated_at)
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.8)
            );
        });

        BlogPost::published()->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/blog/{$post->slug}"))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency('monthly')
                    ->setPriority(0.6)
            );
        });

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function cities(): Response
    {
        $sitemap = Sitemap::create();

        ServicePage::published()
            ->with('city')
            ->where('service_type', 'general')
            ->each(function (ServicePage $page) use ($sitemap) {
                $sitemap->add(
                    Url::create(url("/{$page->slug}"))
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency('weekly')
                        ->setPriority(0.8)
                );
            });

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function blog(): Response
    {
        $sitemap = Sitemap::create();

        BlogPost::published()->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/blog/{$post->slug}"))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency('monthly')
                    ->setPriority(0.6)
            );
        });

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
