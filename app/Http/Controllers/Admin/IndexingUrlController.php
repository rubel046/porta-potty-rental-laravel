<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndexingUrl;
use App\Services\GoogleIndexingService;
use Illuminate\Http\Request;

class IndexingUrlController extends Controller
{
    public function __construct(
        protected GoogleIndexingService $indexingService
    ) {}

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

        if ($request->action === 'delete') {
            foreach ($urls as $url) {
                $url->delete();
            }

            return redirect()->back()->with('success', 'Selected URLs have been removed');
        }

        if ($request->action === 'submit') {
            $urlStrings = $urls->pluck('url')->toArray();
            $results = $this->indexingService->indexUrls($urlStrings);

            foreach ($urls as $url) {
                if (in_array($url->url, $results['urls'])) {
                    $url->update([
                        'status' => 'submitted',
                        'requested_at' => now(),
                    ]);
                } elseif (! empty($results['errors'])) {
                    $urlError = collect($results['errors'])->first(fn ($e) => str_contains($e, $url->url));
                    if ($urlError) {
                        $url->update([
                            'status' => 'failed',
                            'error_message' => $urlError,
                        ]);
                    }
                }
            }

            $message = $results['submitted'] > 0
                ? "{$results['submitted']} URLs submitted for indexing"
                : 'Failed to submit URLs';

            if (! empty($results['errors'])) {
                $message .= '. Some errors occurred.';
            }

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back();
    }
}
