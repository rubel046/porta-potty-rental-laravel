<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\CallLog;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainState;
use App\Models\IndexingUrl;
use App\Models\PhoneNumber;
use App\Models\ServicePage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $domain = Domain::current();
        $domainId = $domain?->id;

        $todayQuery = CallLog::today();
        $weekQuery = CallLog::thisWeek();
        $monthQuery = CallLog::thisMonth();

        if ($domainId) {
            $todayQuery->where('domain_id', $domainId);
            $weekQuery->where('domain_id', $domainId);
            $monthQuery->where('domain_id', $domainId);
        }

        // Today's Stats
        $todayStats = [
            'total_calls' => (clone $todayQuery)->count(),
            'qualified_calls' => (clone $todayQuery)->qualified()->count(),
            'billable_calls' => (clone $todayQuery)->billable()->count(),
            'revenue' => (clone $todayQuery)->billable()->sum('payout'),
            'cost' => (clone $todayQuery)->sum('cost'),
            'profit' => (clone $todayQuery)->billable()->sum('payout') - (clone $todayQuery)->sum('cost'),
            'avg_duration' => (clone $todayQuery)->where('duration_seconds', '>', 0)->avg('duration_seconds') ?? 0,
            'qualification_rate' => $this->calcRate(
                (clone $todayQuery)->qualified()->count(),
                (clone $todayQuery)->count()
            ),
        ];

        // This Week Stats
        $weekStats = [
            'total_calls' => (clone $weekQuery)->count(),
            'qualified_calls' => (clone $weekQuery)->qualified()->count(),
            'revenue' => (clone $weekQuery)->billable()->sum('payout'),
            'profit' => (clone $weekQuery)->billable()->sum('payout') - (clone $weekQuery)->sum('cost'),
        ];

        // This Month Stats
        $monthStats = [
            'total_calls' => (clone $monthQuery)->count(),
            'qualified_calls' => (clone $monthQuery)->qualified()->count(),
            'revenue' => (clone $monthQuery)->billable()->sum('payout'),
            'cost' => (clone $monthQuery)->sum('cost'),
            'profit' => (clone $monthQuery)->billable()->sum('payout') - (clone $monthQuery)->sum('cost'),
        ];

        // Recent Calls
        $recentCallsQuery = CallLog::with(['city', 'buyer']);
        if ($domainId) {
            $recentCallsQuery->where('domain_id', $domainId);
        }
        $recentCalls = $recentCallsQuery->latest()->take(20)->get();

        // Top Cities by Calls (this month, qualified)
        $topCitiesQuery = CallLog::thisMonth()->qualified();
        if ($domainId) {
            $topCitiesQuery->where('domain_id', $domainId);
        }
        $topCities = $topCitiesQuery
            ->selectRaw('city_id, COUNT(*) as call_count, SUM(payout) as total_revenue')
            ->groupBy('city_id')
            ->orderByDesc('call_count')
            ->with('city')
            ->take(10)
            ->get();

        // Traffic Source Breakdown
        $trafficSourcesQuery = CallLog::thisMonth();
        if ($domainId) {
            $trafficSourcesQuery->where('domain_id', $domainId);
        }
        $trafficSources = $trafficSourcesQuery
            ->selectRaw('traffic_source, COUNT(*) as total, SUM(CASE WHEN is_qualified = 1 THEN 1 ELSE 0 END) as qualified')
            ->groupBy('traffic_source')
            ->get();

        // Daily Call Chart (last 30 days)
        $dailyCallsQuery = CallLog::where('created_at', '>=', now()->subDays(30));
        if ($domainId) {
            $dailyCallsQuery->where('domain_id', $domainId);
        }
        $dailyCalls = $dailyCallsQuery
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN is_billable = 1 THEN 1 ELSE 0 END) as billable')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Active Resources — cities/states with service pages / total in domain
        $generatedCities = ServicePage::when($domainId, fn ($q) => $q->where('domain_id', $domainId))
            ->distinct('city_id')
            ->count('city_id');
        $totalCities = $domain
            ? $domain->cities()->count()
            : City::count();
        $generatedStates = ServicePage::when($domainId, fn ($q) => $q->where('domain_id', $domainId))
            ->join('cities', 'service_pages.city_id', '=', 'cities.id')
            ->distinct('cities.state_id')
            ->count('cities.state_id');
        $totalStates = $domain
            ? DomainState::where('domain_id', $domainId)->distinct('state_id')->count('state_id')
            : DomainState::distinct('state_id')->count('state_id');

        $domainHost = $domain?->domain;
        if ($domainHost) {
            $indexedLinks = IndexingUrl::where('indexed', true)
                ->where(fn ($q) => $q->where('url', 'like', "https://{$domainHost}/%")->orWhere('url', 'like', "http://{$domainHost}/%"))
                ->count();
            $totalIndexedCandidates = IndexingUrl::where(fn ($q) => $q->where('url', 'like', "https://{$domainHost}/%")->orWhere('url', 'like', "http://{$domainHost}/%"))
                ->count();
        } else {
            $indexedLinks = IndexingUrl::where('indexed', true)->count();
            $totalIndexedCandidates = IndexingUrl::count();
        }

        $resourceStats = [
            'published_pages' => $generatedCities.' / '.$totalCities,
            'generated_states' => $generatedStates.' / '.$totalStates,
            'indexed_links' => $indexedLinks.' / '.$totalIndexedCandidates,
            'active_numbers' => PhoneNumber::where('is_active', true)->when($domainId, fn ($q) => $q->where('domain_id', $domainId))->count(),
            'active_buyers' => Buyer::active()->when($domainId, fn ($q) => $q->where('domain_id', $domainId))->count(),
        ];

        return view('admin.dashboard', compact(
            'todayStats', 'weekStats', 'monthStats',
            'recentCalls', 'topCities', 'trafficSources',
            'dailyCalls', 'resourceStats'
        ));
    }

    public function calls(Request $request)
    {
        $query = CallLog::with(['city', 'buyer', 'phoneNumber'])
            ->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('qualified')) {
            $query->where('is_qualified', $request->qualified === 'yes');
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('buyer_id')) {
            $query->where('buyer_id', $request->buyer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('source')) {
            $query->where('traffic_source', $request->source);
        }

        $calls = $query->paginate(50);

        $cities = City::active()->orderBy('name')->get();
        $buyers = Buyer::orderBy('company_name')->get();

        return view('admin.calls.index', compact('calls', 'cities', 'buyers'));
    }

    protected function calcRate(int $part, int $total): float
    {
        if ($total === 0) {
            return 0;
        }

        return round(($part / $total) * 100, 1);
    }
}
