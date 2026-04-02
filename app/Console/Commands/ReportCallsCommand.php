<?php

namespace App\Console\Commands;

use App\Models\CallLog;
use Illuminate\Console\Command;

class ReportCallsCommand extends Command
{
    protected $signature = 'report:calls {--period=today : Period to report (today, week, month)}';

    protected $description = 'Display call report summary';

    public function handle(): int
    {
        $period = $this->option('period');

        $query = match ($period) {
            'today' => CallLog::today(),
            'week' => CallLog::thisWeek(),
            'month' => CallLog::thisMonth(),
            default => CallLog::query(),
        };

        $calls = $query->get();
        $qualified = $calls->filter(fn ($c) => $c->is_qualified);
        $totalDuration = $calls->sum('duration_seconds');
        $totalPayout = $calls->sum('payout');

        $this->info("Call Report - {$period}");
        $this->line(str_repeat('=', 50));

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Calls', $calls->count()],
                ['Qualified Calls', $qualified->count()],
                ['Duplicate Calls', $calls->where('is_duplicate', true)->count()],
                ['Total Duration', $this->formatDuration($totalDuration)],
                ['Total Payout', '$'.number_format($totalPayout, 2)],
                ['Conversion Rate', $calls->count() > 0 ? round(($qualified->count() / $calls->count()) * 100, 1).'%' : '0%'],
            ]
        );

        $topCities = $calls->groupBy('city_id')
            ->map(fn ($group) => [
                'city' => $group->first()->city?->name ?? 'Unknown',
                'calls' => $group->count(),
                'qualified' => $group->where('is_qualified', true)->count(),
            ])
            ->sortByDesc('calls')
            ->take(5);

        if ($topCities->isNotEmpty()) {
            $this->line("\nTop Cities:");
            $this->table(['City', 'Calls', 'Qualified'], $topCities->values()->toArray());
        }

        return Command::SUCCESS;
    }

    protected function formatDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        return $hours > 0 ? "{$hours}h {$minutes}m {$secs}s" : "{$minutes}m {$secs}s";
    }
}
