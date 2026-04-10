<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'logo_url',
        'primary_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'domain_cities')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function activeCities(): BelongsToMany
    {
        return $this->cities()->wherePivot('status', true);
    }

    public function domainCities(): HasMany
    {
        return $this->hasMany(DomainCity::class);
    }

    public function getCitiesCountAttribute(): int
    {
        return $this->domainCities()->count();
    }

    public function buyers(): HasMany
    {
        return $this->hasMany(Buyer::class);
    }

    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function blogCategories(): HasMany
    {
        return $this->hasMany(BlogCategory::class);
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function domainStates(): HasMany
    {
        return $this->hasMany(DomainState::class);
    }

    public function activeDomainStates(): HasMany
    {
        return $this->hasMany(DomainState::class)->where('status', true);
    }

    public static function current(): ?self
    {
        return session('current_domain_id')
            ? static::find(session('current_domain_id'))
            : static::where('domain', request()->getHost())->first();
    }

    public static function setCurrent(self $domain): void
    {
        session(['current_domain_id' => $domain->id]);
    }
}
