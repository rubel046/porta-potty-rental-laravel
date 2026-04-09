<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'state_id', 'name', 'slug', 'county', 'area_codes',
        'population', 'latitude', 'longitude', 'nearby_cities',
        'zip_codes', 'meta_title', 'meta_description',
        'city_description', 'climate_info', 'local_events',
        'construction_info', 'priority', 'is_active',
    ];

    protected $casts = [
        'nearby_cities' => 'array',
        'zip_codes' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // Relationships
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function servicePages(): HasMany
    {
        return $this->hasMany(ServicePage::class);
    }

    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function domains(): BelongsToMany
    {
        return $this->belongsToMany(Domain::class, 'domain_cities')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function activeDomains(): BelongsToMany
    {
        return $this->domains()->wherePivot('status', true);
    }

    public function domainCities(): HasMany
    {
        return $this->hasMany(DomainCity::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByState(Builder $query, string $stateCode): Builder
    {
        return $query->whereHas('state', fn ($q) => $q->where('code', $stateCode));
    }

    public function scopeByPriority(Builder $query): Builder
    {
        return $query->orderByDesc('priority')->orderBy('name');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->name}, {$this->state->code}";
    }

    public function getActivePhoneAttribute(): ?string
    {
        return $this->phoneNumbers()
            ->where('is_active', true)
            ->first()?->friendly_name;
    }

    public function getActivePhoneRawAttribute(): ?string
    {
        return $this->phoneNumbers()
            ->where('is_active', true)
            ->first()?->number;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Helper Methods
    public function getServicePage(string $type = 'general'): ?ServicePage
    {
        return $this->servicePages()
            ->where('service_type', $type)
            ->where('is_published', true)
            ->first();
    }

    public function getActiveFaqs(?string $serviceType = null): Collection
    {
        return $this->faqs()
            ->where('is_active', true)
            ->when($serviceType, fn ($q) => $q->where('service_type', $serviceType))
            ->orderBy('sort_order')
            ->get();
    }

    public function getNearbyAreaNames(): array
    {
        return $this->nearby_cities ?? [];
    }
}
