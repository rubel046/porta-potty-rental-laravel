<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        'domain_id', 'name', 'code', 'slug', 'timezone', 'is_active',
        'h1_title', 'meta_title', 'meta_description',
        'content', 'images', 'word_count', 'seo_score',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
        'seo_score' => 'float',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function activeCities(): HasMany
    {
        return $this->hasMany(City::class)->where('is_active', true);
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class, 'domain_states')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function activeDomains(): BelongsToMany
    {
        return $this->domains()->wherePivot('status', true);
    }

    public function domainStates(): HasMany
    {
        return $this->hasMany(DomainState::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getUrlAttribute(): string
    {
        return url("/porta-potty-rental-{$this->slug}");
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title
            ?? "Porta Potty Rental in {$this->name} | Same-Day Delivery | Potty Direct";
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description
            ?? "Find affordable porta potty rental in {$this->name}. Same-day delivery available in {$this->cities()->count()} cities. Construction, events, weddings & more. Call for a free quote!";
    }

    public function hasContent(): bool
    {
        return ! empty($this->content);
    }
}
