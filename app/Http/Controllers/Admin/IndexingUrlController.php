<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
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
        $domainHost = $this->currentDomainHost();

        $baseQuery = fn () => $this->scopedQuery();

        $query = $baseQuery()->orderByDesc('created_at');

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

        // Aggregate stats in one query instead of 5
        $statsRow = $baseQuery()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending'   THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN status = 'indexed'   THEN 1 ELSE 0 END) as indexed,
                SUM(CASE WHEN status = 'failed'    THEN 1 ELSE 0 END) as failed
            ")
            ->first();

        $stats = [
            'total' => (int) ($statsRow->total ?? 0),
            'pending' => (int) ($statsRow->pending ?? 0),
            'submitted' => (int) ($statsRow->submitted ?? 0),
            'indexed' => (int) ($statsRow->indexed ?? 0),
            'failed' => (int) ($statsRow->failed ?? 0),
        ];

        return view('admin.indexing-urls.index', compact('urls', 'stats'));
    }

    public function destroy(IndexingUrl $indexingUrl)
    {
        $this->authorizeDomain($indexingUrl);
        $indexingUrl->delete();

        return redirect()->back()->with('success', 'URL removed from indexing queue');
    }

    public function batch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|in:submit,delete',
        ]);

        // Scope to current domain — don't allow operating on another tenant's URLs.
        $urls = $this->scopedQuery()->whereIn('id', $request->ids)->get();

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

    /**
     * IndexingUrl has no domain_id column — URLs reference the site's host.
     * Scope by URL prefix matching the current domain.
     */
    protected function scopedQuery()
    {
        $host = $this->currentDomainHost();

        if (! $host) {
            return IndexingUrl::query();
        }

        return IndexingUrl::query()
            ->where(function ($q) use ($host) {
                $q->where('url', 'like', "https://{$host}/%")
                    ->orWhere('url', 'like', "http://{$host}/%");
            });
    }

    protected function authorizeDomain(IndexingUrl $url): void
    {
        $host = $this->currentDomainHost();

        if (! $host) {
            return;
        }

        if (
            ! str_starts_with($url->url, "https://{$host}/")
            && ! str_starts_with($url->url, "http://{$host}/")
        ) {
            abort(404);
        }
    }

    protected function currentDomainHost(): ?string
    {
        return Domain::current()?->domain;
    }
}
