<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    protected $fillable = [
        'company_name', 'contact_name', 'phone', 'backup_phone',
        'email', 'payout_per_call', 'daily_call_cap', 'monthly_call_cap',
        'concurrent_call_limit', 'serving_states', 'serving_cities',
        'business_hours', 'timezone', 'ring_timeout', 'priority',
        'total_billed', 'total_calls', 'balance',
        'payment_method', 'notes', 'is_active',
    ];

    protected $casts = [
        'serving_states' => 'array',
        'serving_cities' => 'array',
        'business_hours' => 'array',
        'is_active' => 'boolean',
        'payout_per_call' => 'decimal:2',
        'total_billed' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function isWithinBusinessHours(): bool
    {
        $now = now()->setTimezone($this->timezone);
        $hours = $this->business_hours;

        if (! $hours || ! isset($hours['start'], $hours['end'])) {
            return true; // no hours set = always available
        }

        $start = Carbon::parse($hours['start'], $this->timezone);
        $end = Carbon::parse($hours['end'], $this->timezone);

        return $now->between($start, $end);
    }

    public function hasReachedDailyCap(): bool
    {
        return CallLog::buyerDailyCallCount($this->id) >= $this->daily_call_cap;
    }

    public function hasReachedMonthlyCap(): bool
    {
        $monthlyCount = $this->callLogs()
            ->billable()
            ->thisMonth()
            ->count();

        return $monthlyCount >= $this->monthly_call_cap;
    }

    public function isAvailable(): bool
    {
        return $this->is_active
            && $this->isWithinBusinessHours()
            && ! $this->hasReachedDailyCap()
            && ! $this->hasReachedMonthlyCap();
    }

    public function getTodayCallsAttribute(): int
    {
        return CallLog::buyerDailyCallCount($this->id);
    }

    public function getThisMonthRevenueAttribute(): float
    {
        return (float) $this->callLogs()
            ->billable()
            ->thisMonth()
            ->sum('payout');
    }
}
