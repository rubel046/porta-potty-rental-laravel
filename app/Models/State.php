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
        'name', 'code', 'slug', 'timezone', 'is_active', 'views',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function incrementViews(): void
    {
        $this->increment('views');
    }

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
        $domain = Domain::current() ?? Domain::first();
        $slugPrefix = $domain?->getServiceSlugPrefix() ?? 'service';

        return url("/{$slugPrefix}-rental-{$this->slug}");
    }

    public function getSeoTitleAttribute(): ?string
    {
        $domain = Domain::current() ?? Domain::first();
        $domainState = null;

        if ($domain) {
            $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();
        }

        if ($domainState?->meta_title) {
            return $domainState->meta_title;
        }

        if ($this->meta_title) {
            return $this->meta_title;
        }

        $keyword = $domain?->primary_keyword ?? 'service rental';

        return "{$keyword} in {$this->name} | Same-Day Delivery";
    }

    public function getSeoDescriptionAttribute(): ?string
    {
        $domain = Domain::current() ?? Domain::first();
        $domainState = null;

        if ($domain) {
            $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();
        }

        if ($domainState?->meta_description) {
            return str_replace('{{PHONE_LINK}}', domain_phone_display(), $domainState->meta_description);
        }

        if ($this->meta_description) {
            return $this->meta_description;
        }

        $keyword = $domain?->primary_keyword ?? 'service rental';

        return "Find affordable {$keyword} in {$this->name}. Same-day delivery available.";
    }

    public function hasContent(): bool
    {
        $domain = Domain::current();

        if ($domain) {
            $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

            return $domainState?->content || $this->content;
        }

        return ! empty($this->content);
    }

    public function getContentAttribute(): ?string
    {
        $domain = Domain::current();

        if ($domain) {
            $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

            return $domainState?->content ?? $this->attributes['content'] ?? null;
        }

        return $this->attributes['content'] ?? null;
    }

    public function getImagesAttribute(): ?array
    {
        $domain = Domain::current();

        if (! $domain) {
            return null;
        }

        $domainState = $this->domainStates()->where('domain_id', $domain->id)->first();

        return $domainState?->images;
    }
}
