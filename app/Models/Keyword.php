<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    protected $fillable = [
        'domain_id',
        'keyword',
        'volume',
        'competition',
        'cpc',
        'service_type',
        'tier',
        'is_active',
    ];

    protected $casts = [
        'volume' => 'integer',
        'cpc' => 'decimal:2',
        'tier' => 'integer',
        'is_active' => 'boolean',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowCompetition($query)
    {
        return $query->whereIn('competition', ['low', null]);
    }

    public function scopeByServiceType($query, ?string $serviceType)
    {
        if ($serviceType) {
            return $query->where(function ($q) use ($serviceType) {
                $q->where('service_type', $serviceType)
                  ->orWhereNull('service_type');
            });
        }

        return $query;
    }

    public function scopeTier($query, int $tier)
    {
        return $query->where('tier', $tier);
    }

    public function scopeHighVolume($query, int $minVolume = 200)
    {
        return $query->where('volume', '>=', $minVolume);
    }
}
