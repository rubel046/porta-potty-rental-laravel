<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\CallLog;
use App\Models\City;
use App\Models\PhoneNumber;
use App\Models\ServicePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's Stats
        $todayStats = [
            'total_calls' => CallLog::today()->count(),
            'qualified_calls' => CallLog::today()->qualified()->count(),
            'billable_calls' => CallLog::today()->billable()->count(),
            'revenue' => CallLog::today()->billable()->sum('payout'),
            'cost' => CallLog::today()->sum('cost'),
            'profit' => CallLog::today()->billable()->sum('payout') - CallLog::today()->sum('cost'),
            'avg_duration' => CallLog::today()->where('duration_seconds', '>', 0)->avg('duration_seconds') ?? 0,
            'qualification_rate' => $this->calcRate(
                CallLog::today()->qualified()->count(),
                CallLog::today()->count()
            ),
        ];

        // This Week Stats
        $weekStats = [
            'total_calls' => CallLog::thisWeek()->count(),
            'qualified_calls' => CallLog::thisWeek()->qualified()->count(),
            'revenue' => CallLog::thisWeek()->billable()->sum('payout'),
            'profit' => CallLog::thisWeek()->billable()->sum('payout') - CallLog::thisWeek()->sum('cost'),
        ];

        // This Month Stats
        $monthStats = [
            'total_calls' => CallLog::thisMonth()->count(),
            'qualified_calls' => CallLog::thisMonth()->qualified()->count(),
            'revenue' => CallLog::thisMonth()->billable()->sum('payout'),
            'cost' => CallLog::thisMonth()->sum('cost'),
            'profit' => CallLog::thisMonth()->billable()->sum('payout') - CallLog::thisMonth()->sum('cost'),
        ];

        // Recent Calls
        $recentCalls = CallLog::with(['city', 'buyer'])
            ->latest()
            ->take(20)
            ->get();

        // Top Cities by Calls
        $topCities = CallLog::thisMonth()
            ->qualified()
            ->selectRaw('city_id, COUNT(*) as call_count, SUM(payout) as total_revenue')
            ->groupBy('city_id')
            ->orderByDesc('call_count')
            ->with('city')
            ->take(10)
            ->get();

        // Traffic Source Breakdown
        $trafficSources = CallLog::thisMonth()
            ->selectRaw('traffic_source, COUNT(*) as total, SUM(CASE WHEN is_qualified = 1 THEN 1 ELSE 0 END) as qualified')
            ->groupBy('traffic_source')
            ->get();

        // Daily Call Chart (last 30 days)
        $dailyCalls = CallLog::selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN is_billable = 1 THEN 1 ELSE 0 END) as billable')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Active Resources
        $generatedCities = ServicePage::where('generation_status', 'success')->distinct('city_id')->count('city_id');
        $totalCities = DB::table('domain_cities')->distinct('city_id')->count('city_id');
        $generatedStates = ServicePage::join('cities', 'service_pages.city_id', '=', 'cities.id')
            ->where('service_pages.generation_status', 'success')
            ->distinct('cities.state_id')
            ->count('cities.state_id');
        $totalStates = DB::table('domain_states')->count();

        $resourceStats = [
            'published_pages' => $generatedCities.' / '.$totalCities,
            'generated_states' => $generatedStates.' / '.$totalStates,
            'active_numbers' => PhoneNumber::where('is_active', true)->count(),
            'active_buyers' => Buyer::active()->count(),
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
