<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
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
            ->paginate(10)
            ->appends($request->query());

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with('category', 'city')
            ->firstOrFail();

        $post->increment('views');

        return view('blog.show', compact('post'));
    }
}
