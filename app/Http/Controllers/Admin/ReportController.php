<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\City;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = match ($period) {
            'today' => today(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            'all' => CallLog::min('created_at') ?? today(),
            default => now()->startOfMonth(),
        };
        $endDate = now();

        // Overall stats
        $revenue = CallLog::whereBetween('created_at', [$startDate, $endDate])->billable()->sum('payout');
        $cost = CallLog::whereBetween('created_at', [$startDate, $endDate])->sum('cost');

        $stats = [
            'total_calls' => CallLog::whereBetween('created_at', [$startDate, $endDate])->count(),
            'qualified_calls' => CallLog::whereBetween('created_at', [$startDate, $endDate])->qualified()->count(),
            'billable_calls' => CallLog::whereBetween('created_at', [$startDate, $endDate])->billable()->count(),
            'total_revenue' => $revenue,
            'total_cost' => $cost,
            'total_profit' => $revenue - $cost,
            'revenue' => $revenue,
            'cost' => $cost,
            'profit' => $revenue - $cost,
            'avg_duration' => CallLog::whereBetween('created_at', [$startDate, $endDate])
                ->where('duration_seconds', '>', 0)->avg('duration_seconds') ?? 0,
        ];
        $stats['roi'] = $stats['cost'] > 0 ? round((($stats['revenue'] - $stats['cost']) / $stats['cost']) * 100, 1) : 0;
        $stats['qualification_rate'] = $stats['total_calls'] > 0
            ? round(($stats['qualified_calls'] / $stats['total_calls']) * 100, 1) : 0;
        $stats['cost_per_call'] = $stats['billable_calls'] > 0
            ? round($stats['cost'] / $stats['billable_calls'], 2) : 0;

        // By City
        $cityStats = CallLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('city_id, COUNT(*) as total, SUM(CASE WHEN is_billable=1 THEN 1 ELSE 0 END) as billable, SUM(payout) as revenue, SUM(cost) as cost')
            ->groupBy('city_id')
            ->orderByDesc('revenue')
            ->with('city.state')
            ->get();

        // By Source
        $sourceStats = CallLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('traffic_source, COUNT(*) as total, SUM(CASE WHEN is_billable=1 THEN 1 ELSE 0 END) as billable, SUM(payout) as revenue')
            ->groupBy('traffic_source')
            ->orderByDesc('revenue')
            ->get();

        // By Day
        $dailyStats = CallLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN is_billable=1 THEN 1 ELSE 0 END) as billable, SUM(payout) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Disqualification reasons
        $dqReasons = CallLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('disqualification_reason')
            ->selectRaw('disqualification_reason, COUNT(*) as count')
            ->groupBy('disqualification_reason')
            ->orderByDesc('count')
            ->get();

        return view('admin.reports.index', compact(
            'period', 'startDate', 'endDate', 'stats',
            'cityStats', 'sourceStats', 'dailyStats', 'dqReasons'
        ));
    }
}
