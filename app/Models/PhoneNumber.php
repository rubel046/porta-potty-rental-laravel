<?php

namespace App\Models;

use App\Services\SignalWireService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhoneNumber extends Model
{
    protected $fillable = [
        'domain_id', 'number', 'friendly_name', 'area_code', 'city_id', 'buyer_id',
        'provider', 'provider_sid', 'monthly_cost', 'status',
        'total_calls', 'is_active',
    ];

    protected $casts = [
        'monthly_cost' => 'decimal:4',
        'total_calls' => 'integer',
        'is_active' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function getFormattedAttribute(): string
    {
        return $this->friendly_name ?? (new SignalWireService)->formatPhone($this->number);
    }

    public function getRawNumberAttribute(): string
    {
        return preg_replace('/[^0-9+]/', '', $this->number);
    }
}
