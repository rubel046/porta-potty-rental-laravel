<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Neighborhood extends Model
{
    protected $fillable = [
        'city_id', 'name', 'slug', 'description', 'local_landmarks',
        'neighborhood_type', 'latitude', 'longitude', 'is_active', 'priority',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function servicePages(): HasMany
    {
        return $this->hasMany(NeighborhoodServicePage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderByDesc('priority')->orderBy('name');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name}, {$this->city->name}";
    }

    public function getUrlAttribute(): string
    {
        return url("neighborhoods/{$this->slug}");
    }
}
