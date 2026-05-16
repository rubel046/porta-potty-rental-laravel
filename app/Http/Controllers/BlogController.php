<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Domain;
use App\Providers\DomainViewHelper;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $domain = Domain::current();
        $domainId = $domain?->id;

        $categories = BlogCategory::where('is_active', true)
            ->when($domainId, fn ($q) => $q->where('domain_id', $domainId))
            ->withCount(['publishedPosts as posts_count'])
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = null;
        if ($request->category) {
            $selectedCategory = BlogCategory::where('slug', $request->category)
                ->when($domainId, fn ($q) => $q->where('domain_id', $domainId))
                ->first();
        }

        $sort = $request->get('sort', 'latest');

        $totalPostsCount = BlogPost::published()
            ->when($domainId, fn ($q) => $q->where('domain_id', $domainId))
            ->count();

        $posts = BlogPost::published()
            ->with('category')
            ->when($domainId, fn ($q) => $q->where('domain_id', $domainId))
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('blog_category_id', $selectedCategory->id);
            })
            ->when($sort === 'popular', function ($query) {
                $query->orderBy('views', 'desc');
            }, function ($query) {
                $query->latest('published_at');
            })
            ->paginate(12)
            ->appends($request->query());

        $featuredPosts = collect();
        if (! $selectedCategory) {
            $featuredPosts = BlogPost::published()
                ->with('category')
                ->featured()
                ->when($domainId, fn ($q) => $q->where('domain_id', $domainId))
                ->latest('published_at')
                ->limit(3)
                ->get();
        }

        $paginationHeaders = '';
        if ($posts->currentPage() > 1) {
            $paginationHeaders .= '<link rel="prev" href="'.$posts->previousPageUrl().'">'."\n";
        }
        if ($posts->hasMorePages()) {
            $paginationHeaders .= '<link rel="next" href="'.$posts->nextPageUrl().'">'."\n";
        }

        $categorySeo = $selectedCategory ? [
            'description' => $selectedCategory->description ?? '',
            'name' => $selectedCategory->name ?? '',
        ] : null;

        return view(DomainViewHelper::resolveForController('blog-index'), compact(
            'posts', 'categories', 'selectedCategory', 'featuredPosts', 'paginationHeaders', 'totalPostsCount', 'categorySeo'
        ));
    }

    public function indexByCategory(string $slug, Request $request)
    {
        $domain = Domain::current();
        $category = BlogCategory::where('slug', $slug)->where('is_active', true)
            ->when($domain, fn ($q) => $q->where('domain_id', $domain->id))
            ->firstOrFail();
        $request->merge(['category' => $slug]);

        return $this->index($request);
    }

    public function show(string $slug, ContentGeneratorService $contentService)
    {
        $domain = Domain::current();

        $post = BlogPost::where('slug', $slug)
            ->published()
            ->when($domain, fn ($q) => $q->where('domain_id', $domain->id))
            ->with('category', 'city')
            ->firstOrFail();

        $post->increment('views');

        $city = $post->city;
        if ($city) {
            $post->content = $contentService->ensureServiceLinks($post->content, $city);
            $post->excerpt = $contentService->ensureServiceLinks($post->excerpt ?? '', $city);
        } else {
            $post->content = $contentService->ensureServiceLinks($post->content);
        }

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('blog_category_id', $post->blog_category_id)
                  ->orWhere('city_id', $post->city_id);
            })
            ->take(3)
            ->get();

        return view(DomainViewHelper::resolveForController('blog-show'), compact('post', 'relatedPosts', 'domain'));
    }
}
