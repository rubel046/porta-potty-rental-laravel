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

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }

        $pages = $query->orderBy('slug')->paginate(30);
        $cities = City::active()->with('state')->orderBy('name')->get();

        return view('admin.service-pages.index', compact('pages', 'cities'));
    }

    public function edit(ServicePage $servicePage)
    {
        $servicePage->load('city.state');

        return view('admin.service-pages.edit', ['page' => $servicePage]);
    }

    public function update(Request $request, ServicePage $servicePage)
    {
        $validated = $request->validate([
            'h1_title' => 'required|string|max:250',
            'meta_title' => 'required|string|max:200',
            'meta_description' => 'required|string|max:500',
            'content' => 'required|string|min:500',
            'is_published' => 'boolean',
        ]);

        $validated['word_count'] = str_word_count(strip_tags($validated['content']));

        $servicePage->update($validated);
        $servicePage->calculateSeoScore();

        return redirect()->route('admin.service-pages.index')
            ->with('success', 'Service page updated!');
    }
}
