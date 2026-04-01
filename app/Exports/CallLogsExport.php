<?php

namespace App\Exports;

use App\Models\CallLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallLogsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected $startDate,
        protected $endDate
    ) {}

    public function collection(): Collection
    {
        return CallLog::with(['buyer', 'city', 'phoneNumber'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (CallLog $call) => [
                $call->id,
                $call->call_sid,
                $call->created_at->format('Y-m-d H:i:s'),
                $call->caller_number,
                $call->called_number,
                $call->city?->name ?? 'N/A',
                $call->buyer?->company_name ?? 'N/A',
                $call->status,
                $call->is_qualified ? 'Yes' : 'No',
                $call->is_billable ? 'Yes' : 'No',
                $call->duration_formatted,
                number_format($call->payout, 2),
                number_format($call->cost, 2),
                number_format($call->profit ?? 0, 2),
                $call->disqualification_reason ?? 'N/A',
                $call->traffic_source ?? 'N/A',
            ]);
    }

    public function headings(): array
    {
        return [
            'ID', 'Call SID', 'Date', 'Caller', 'Called', 'City', 'Buyer',
            'Status', 'Qualified', 'Billable', 'Duration', 'Payout', 'Cost', 'Profit',
            'Disqualification Reason', 'Traffic Source',
        ];
    }
}
