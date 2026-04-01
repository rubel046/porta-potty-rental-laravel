<?php

namespace App\Exports;

use App\Models\CallLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected $startDate,
        protected $endDate
    ) {}

    public function collection(): Collection
    {
        return CallLog::selectRaw('buyer_id, COUNT(*) as call_count, SUM(payout) as total_revenue, SUM(cost) as total_cost, SUM(payout) - SUM(cost) as profit')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('is_billable', true)
            ->whereNotNull('buyer_id')
            ->groupBy('buyer_id')
            ->with('buyer')
            ->get()
            ->map(fn ($row) => [
                $row->buyer?->company_name ?? 'Unknown',
                $row->buyer?->contact_name ?? 'N/A',
                $row->buyer?->email ?? 'N/A',
                $row->call_count,
                number_format($row->total_revenue, 2),
                number_format($row->total_cost, 2),
                number_format($row->profit, 2),
            ]);
    }

    public function headings(): array
    {
        return ['Company', 'Contact', 'Email', 'Call Count', 'Revenue', 'Cost', 'Profit'];
    }
}
