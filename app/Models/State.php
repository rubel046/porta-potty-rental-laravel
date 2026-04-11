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
        'name', 'code', 'slug', 'timezone', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function getSeoTitleAttribute(): ?string
    {
        $domain = Domain::current();
        if (! $domain) {
            return $this->meta_title ?? "Porta Potty Rental in {$this->name} | Same-Day Delivery";
        }

        $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

        return $domainState?->meta_title ?? $this->meta_title ?? "{$domain->primary_service} Rental in {$this->name}";
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        $domain = Domain::current();
        if (! $domain) {
            return $this->meta_description ?? "Find affordable {$domain->primary_service} rental in {$this->name}.";
        }

        $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

        return $domainState?->meta_description ?? $this->meta_description ?? "Find affordable {$domain->primary_service} rental in {$this->name}. Same-day delivery available.";
    }

    public function hasContent(): bool
    {
        $domain = Domain::current();
        if (! $domain) {
            return ! empty($this->content);
        }

        $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

        return $domainState?->content || $this->content;
    }

    public function getContentAttribute(): ?string
    {
        $domain = Domain::current();
        if (! $domain) {
            return $this->attributes['content'] ?? null;
        }

        $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

        return $domainState?->content ?? $this->attributes['content'] ?? null;
    }
}
