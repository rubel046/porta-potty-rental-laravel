<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    protected $fillable = [
        'domain_id', 'call_sid', 'caller_number', 'called_number', 'forwarded_to',
        'phone_number_id', 'city_id', 'buyer_id', 'service_page_id',
        'duration_seconds', 'ring_duration', 'status',
        'is_qualified', 'is_duplicate', 'is_billable', 'ivr_passed',
        'disqualification_reason',
        'payout', 'cost', 'profit',
        'caller_city', 'caller_state', 'caller_zip', 'caller_country',
        'recording_url', 'recording_duration',
        'traffic_source', 'landing_page', 'utm_source', 'utm_medium', 'utm_campaign',
        'buyer_disposition', 'buyer_notes',
        'call_started_at', 'call_answered_at', 'call_ended_at',
    ];

    protected $casts = [
        'is_qualified' => 'boolean',
        'is_duplicate' => 'boolean',
        'is_billable' => 'boolean',
        'ivr_passed' => 'boolean',
        'payout' => 'decimal:2',
        'cost' => 'decimal:2',
        'profit' => 'decimal:2',
        'call_started_at' => 'datetime',
        'call_answered_at' => 'datetime',
        'call_ended_at' => 'datetime',
    ];

    // Status Constants
    const STATUS_INITIATED = 'initiated';

    const STATUS_RINGING = 'ringing';

    const STATUS_IN_PROGRESS = 'in-progress';

    const STATUS_COMPLETED = 'completed';

    const STATUS_NO_ANSWER = 'no-answer';

    const STATUS_BUSY = 'busy';

    const STATUS_FAILED = 'failed';

    const STATUS_CANCELED = 'canceled';

    // Relationships
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function phoneNumber(): BelongsTo
    {
        return $this->belongsTo(PhoneNumber::class);
    }

    public function servicePage(): BelongsTo
    {
        return $this->belongsTo(ServicePage::class);
    }

    // Scopes
    public function scopeQualified(Builder $query): Builder
    {
        return $query->where('is_qualified', true);
    }

    public function scopeBillable(Builder $query): Builder
    {
        return $query->where('is_billable', true);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeBySource(Builder $query, string $source): Builder
    {
        return $query->where('traffic_source', $source);
    }

    // Accessors
    public function getDurationFormattedAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'completed' => $this->is_qualified ? '✅ Qualified' : '⚠️ Too Short',
            'no-answer' => '📵 No Answer',
            'busy' => '🔴 Busy',
            'failed' => '❌ Failed',
            'in-progress' => '🔄 In Progress',
            default => '⏳ '.ucfirst($this->status),
        };
    }

    public function getCallerLocationAttribute(): string
    {
        $parts = array_filter([$this->caller_city, $this->caller_state]);

        return implode(', ', $parts) ?: 'Unknown';
    }

    // Methods
    public function qualify(): void
    {
        $minDuration = (int) config('services.signalwire.min_duration', 90);

        $isQualified = $this->duration_seconds >= $minDuration
            && ! $this->is_duplicate
            && $this->ivr_passed
            && $this->status === self::STATUS_COMPLETED;

        $payout = 0;
        $reason = null;

        if (! $isQualified) {
            if ($this->duration_seconds < $minDuration) {
                $reason = 'too_short';
            } elseif ($this->is_duplicate) {
                $reason = 'duplicate';
            } elseif (! $this->ivr_passed) {
                $reason = 'no_ivr';
            } else {
                $reason = 'call_failed';
            }
        } else {
            $payout = $this->buyer?->payout_per_call ?? 0;
        }

        $this->update([
            'is_qualified' => $isQualified,
            'is_billable' => $isQualified,
            'payout' => $payout,
            'profit' => $payout - $this->cost,
            'disqualification_reason' => $reason,
        ]);
    }

    public static function isDuplicateCaller(string $callerNumber, int $hours = 72): bool
    {
        return static::where('caller_number', $callerNumber)
            ->where('is_qualified', true)
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }

    public static function buyerDailyCallCount(int $buyerId): int
    {
        return static::where('buyer_id', $buyerId)
            ->where('is_billable', true)
            ->whereDate('created_at', today())
            ->count();
    }
}
