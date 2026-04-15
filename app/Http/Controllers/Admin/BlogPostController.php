<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category', 'city');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        $posts = $query->latest()->paginate(20);

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        $domain = Domain::current();
        $query = BlogCategory::orderBy('name');
        if ($domain) {
            $query->where('domain_id', $domain->id);
        }
        $categories = $query->get();
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

        $domain = Domain::current();
        if ($domain) {
            $validated['domain_id'] = $domain->id;
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
        $domain = Domain::current();
        $query = BlogCategory::orderBy('name');
        if ($domain) {
            $query->where('domain_id', $domain->id);
        }
        $categories = $query->get();
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

    public function generateForm()
    {
        $domain = Domain::current();
        $query = BlogCategory::where('is_active', true);
        if ($domain) {
            $query->where('domain_id', $domain->id);
        }
        $categories = $query->orderBy('name')->get();
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.blog-posts.generate', compact('categories', 'cities'));
    }

    public function generate(Request $request, ContentGeneratorService $generator)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'city_id' => 'nullable|exists:cities,id',
        ]);

        $category = BlogCategory::findOrFail($validated['blog_category_id']);
        $city = isset($validated['city_id']) ? City::findOrFail($validated['city_id']) : null;

        try {
            $result = $generator->generateBlogPostContent($category, $city);

            if (! $result || ! ($result['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to generate content. Please check AI configuration.',
                ], 422);
            }

            $domain = Domain::current();

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $result['title'] ?? '',
                    'slug' => $result['slug'] ?? '',
                    'excerpt' => $result['excerpt'] ?? '',
                    'content' => $result['content'] ?? '',
                    'meta_title' => $result['meta_title'] ?? '',
                    'meta_description' => $result['meta_description'] ?? '',
                    'focus_keyword' => $result['focus_keyword'] ?? '',
                    'secondary_keywords' => $result['secondary_keywords'] ?? [],
                    'blog_category_id' => $category->id,
                    'city_id' => $city?->id,
                    'domain_id' => $domain?->id,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
