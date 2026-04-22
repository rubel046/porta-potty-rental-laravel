<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndexingUrl;
use Illuminate\Http\Request;

class IndexingUrlController extends Controller
{
    public function index(Request $request)
    {
        $query = IndexingUrl::query()->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('url', 'like', '%'.$request->search.'%');
        }

        $urls = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => IndexingUrl::count(),
            'pending' => IndexingUrl::where('status', 'pending')->count(),
            'submitted' => IndexingUrl::where('status', 'submitted')->count(),
            'indexed' => IndexingUrl::where('status', 'indexed')->count(),
            'failed' => IndexingUrl::where('status', 'failed')->count(),
        ];

        return view('admin.indexing-urls.index', compact('urls', 'stats'));
    }

    public function destroy(IndexingUrl $indexingUrl)
    {
        $indexingUrl->delete();

        return redirect()->back()->with('success', 'URL removed from indexing queue');
    }

    public function batch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:submit,delete',
        ]);

        $urls = IndexingUrl::whereIn('id', $request->ids)->get();

        foreach ($urls as $url) {
            if ($request->action === 'delete') {
                $url->delete();
            } elseif ($request->action === 'submit') {
                $url->markAsSubmitted();
            }
        }

        $message = $request->action === 'delete'
            ? 'Selected URLs have been removed'
            : 'Selected URLs have been submitted for indexing';

        return redirect()->back()->with('success', $message);
    }
}
