<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Providers\DomainViewHelper;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = BlogPost::published()
            ->with('category')
            ->when($request->category, function ($query) use ($request) {
                $category = BlogCategory::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('blog_category_id', $category->id);
                }
            })
            ->latest('published_at')
            ->paginate(12)
            ->appends($request->query());

        $paginationHeaders = '';
        if ($posts->currentPage() > 1) {
            $paginationHeaders .= '<link rel="prev" href="'.$posts->previousPageUrl().'">'."\n";
        }
        if ($posts->hasMorePages()) {
            $paginationHeaders .= '<link rel="next" href="'.$posts->nextPageUrl().'">'."\n";
        }

        return view(DomainViewHelper::resolveForController('blog-index'), compact('posts', 'paginationHeaders'));
    }

    public function show(string $slug, ContentGeneratorService $contentService)
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
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

        return view(DomainViewHelper::resolveForController('blog-show'), compact('post'));
    }
}
