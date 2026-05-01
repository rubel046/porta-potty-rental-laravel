<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $domain = Domain::current() ?? Domain::first();
        $query = BlogCategory::query();

        if ($domain) {
            $query->where('domain_id', $domain->id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query->orderBy('sort_order')->paginate(20);

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        $domains = Domain::orderBy('name')->get();
        $domain = Domain::current() ?? Domain::first();

        $nextSortOrder = BlogCategory::where('domain_id', $domain?->id)
            ->max('sort_order') + 1;

        return view('admin.blog-categories.form', compact('domains', 'nextSortOrder'));
    }

    public function store(Request $request)
    {
        $domain = Domain::current();
        $validated = $request->validate([
            'domain_id' => 'required|integer|exists:domains,id',
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(BlogCategory $blogCategory)
    {
        $domains = Domain::orderBy('name')->get();

        return view('admin.blog-categories.form', compact('blogCategory', 'domains'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'domain_id' => 'required|integer|exists:domains,id',
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug,'.$blogCategory->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $blogCategory->update($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
