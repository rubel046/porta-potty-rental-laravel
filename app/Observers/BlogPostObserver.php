<?php

namespace App\Observers;

use App\Http\Controllers\SitemapController;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

class BlogPostObserver
{
    public function saved(BlogPost $post): void
    {
        $this->clearCache($post);
        SitemapController::invalidateCache();
    }

    public function deleted(BlogPost $post): void
    {
        $this->clearCache($post);
        SitemapController::invalidateCache();
    }

    protected function clearCache(BlogPost $post): void
    {
        Cache::forget('home_recent_posts_default');

        if ($post->city_id && ($city = $post->city)) {
            foreach ($city->domains as $domain) {
                Cache::forget("home_recent_posts_{$domain->id}");
            }

            foreach ($city->servicePages as $page) {
                Cache::forget("service_data_{$page->id}");
            }
        }
    }
}
