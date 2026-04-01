<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category', 'city')
            ->latest()
            ->paginate(20);

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.blog-posts.form', compact('categories', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:300',
            'slug' => 'nullable|string|max:300|unique:blog_posts',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'content' => 'required|string|min:100',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:200',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post created!');
    }

    public function edit(BlogPost $blogPost)
    {
        $categories = BlogCategory::orderBy('name')->get();
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.blog-posts.form', [
            'post' => $blogPost,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:300',
            'slug' => 'nullable|string|max:300|unique:blog_posts,slug,'.$blogPost->id,
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'content' => 'required|string|min:100',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:200',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($request->boolean('is_published') && ! $blogPost->published_at) {
            $validated['published_at'] = now();
        }

        $blogPost->update($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated!');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted!');
    }
}
