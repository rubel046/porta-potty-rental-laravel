<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ServicePage;
use Illuminate\Http\Request;

class ServicePageController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicePage::with('city.state');

        if ($request->filled('search')) {
            $query->where('slug', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        if ($request->filled('published')) {
            $query->where('is_published', $request->published);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $servicePages = $query->orderBy('slug')->paginate(30);
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.service-pages.index', compact('servicePages', 'cities'));
    }

    public function edit(ServicePage $servicePage)
    {
        $servicePage->load('city.state');

        return view('admin.service-pages.edit', ['page' => $servicePage]);
    }

    public function show(ServicePage $servicePage)
    {
        $servicePage->load('city.state');

        return view('admin.service-pages.show', ['servicePage' => $servicePage]);
    }

    public function quickView(ServicePage $servicePage)
    {
        return response()->json([
            'h1_title' => $servicePage->h1_title,
            'meta_title' => $servicePage->meta_title,
            'meta_description' => $servicePage->meta_description,
            'content' => $servicePage->content,
            'word_count' => $servicePage->word_count,
            'slug' => $servicePage->slug,
            'service_type' => $servicePage->service_type,
        ]);
    }

    public function update(Request $request, ServicePage $servicePage)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:250|unique:service_pages,slug,'.$servicePage->id,
            'h1_title' => 'required|string|max:250',
            'meta_title' => 'required|string|max:200',
            'meta_description' => 'required|string|max:500',
            'content' => 'required|string|min:500',
            'is_published' => 'boolean',
        ]);

        // Transform phone numbers to clickable tel: links
        $content = $validated['content'];
        $phonePattern = '/(\+?1?[\s\-.]?)?\(?[0-9]{3}\)?[\s\-.]?[0-9]{3}[\s\-.]?[0-9]{4}/';
        $content = preg_replace_callback($phonePattern, function ($matches) {
            $phone = preg_replace('/[^0-9+]/', '', $matches[0]);
            if (strlen($phone) === 10) {
                $phone = '1'.$phone;
            }

            return '<a href="tel:+'.$phone.'" class="text-blue-600 font-semibold hover:underline">'.$matches[0].'</a>';
        }, $content);
        $validated['content'] = $content;

        $validated['word_count'] = str_word_count(strip_tags($content));

        if ($request->filled('slug')) {
            $servicePage->slug = $request->slug;
        }

        $servicePage->update($validated);
        $servicePage->calculateSeoScore();

        return redirect()->route('admin.service-pages.index')
            ->with('success', 'Service page updated!');
    }

    public function destroy(ServicePage $servicePage)
    {
        $cityName = $servicePage->city->name;
        $type = $servicePage->service_type;
        $servicePage->delete();

        return redirect()->back()
            ->with('success', "Deleted {$type} page for {$cityName}!");
    }

    public function bulkDestroy(Request $request)
    {
        $pageIds = $request->input('page_ids', []);

        if (empty($pageIds)) {
            return redirect()->back()->with('error', 'No pages selected');
        }

        $count = ServicePage::whereIn('id', $pageIds)->delete();

        return redirect()->back()->with('success', "Deleted {$count} pages!");
    }
}
