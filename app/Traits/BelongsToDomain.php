<?php

namespace App\Traits;

use App\Models\Domain;
use App\Models\DomainCity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait BelongsToDomain
{
    public static function bootBelongsToDomain(): void
    {
        static::addGlobalScope('domain', function (Builder $builder) {
            $domain = Domain::current();
            if ($domain) {
                $builder->whereHas('domainCities', function ($q) use ($domain) {
                    $q->where('domain_id', $domain->id)
                        ->where('status', true);
                });
            }
        });
    }

    public function domainCities(): HasMany
    {
        return $this->hasMany(DomainCity::class);
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class, 'domain_cities')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function scopeForDomain(Builder $query, ?Domain $domain): Builder
    {
        if ($domain) {
            return $query->whereHas('domainCities', function ($q) use ($domain) {
                $q->where('domain_id', $domain->id);
            });
        }

        return $query;
    }

    public function scopeWithoutDomainScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('domain');
    }

    public function scopeAllDomains(Builder $query): Builder
    {
        return $query->withoutGlobalScope('domain');
    }

    public function scopeForCurrentDomain(Builder $query): Builder
    {
        $domain = Domain::current();
        if ($domain) {
            return $query->whereHas('domainCities', function ($q) use ($domain) {
                $q->where('domain_id', $domain->id);
            });
        }

        return $query;
    }
}
