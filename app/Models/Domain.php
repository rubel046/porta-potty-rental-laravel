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
        'business_name',
        'primary_keyword',
        'secondary_keywords',
        'primary_service',
        'service_types',
        'tagline',
        'cta_phone',
        'logo_url',
        'primary_color',
        'is_active',
        'layout',
        'theme_color',
        'logo_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'service_types' => 'array',
        'secondary_keywords' => 'array',
    ];

    public function servicePages(): HasMany
    {
        return $this->hasMany(ServicePage::class);
    }

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

    public function getServiceTypes(): array
    {
        return $this->service_types ?? [];
    }

    public function getSecondaryKeywords(): array
    {
        return $this->secondary_keywords ?? [];
    }

    public function getSecondaryKeywordsFormatted(): string
    {
        $keywords = $this->getSecondaryKeywords();

        return empty($keywords) ? '' : implode(', ', $keywords);
    }

    public function getServiceTypeLabel(string $type): string
    {
        $labels = [
            'general' => "General {$this->primary_service} Rental",
            'construction' => "Construction Site {$this->primary_service}",
            'wedding' => "Wedding Event {$this->primary_service}",
            'event' => "Event {$this->primary_service} Rental",
            'luxury' => "Luxury {$this->primary_service} Trailer",
            'party' => "Party {$this->primary_service} Rental",
            'emergency' => "Emergency {$this->primary_service}",
            'residential' => "Residential {$this->primary_service}",
            'portable' => "Portable {$this->primary_service} Rental",
        ];

        return $labels[$type] ?? "{$type} {$this->primary_service}";
    }

    public function getServiceSlugPrefix(): string
    {
        return str_replace(' ', '-', $this->primary_service);
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

    public function getLayoutPath(): string
    {
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        $layoutPath = "domains.{$prefix}.layout";

        if (view()->exists($layoutPath)) {
            return $layoutPath;
        }

        return 'domains.pottydirect.layout';
    }

    public static function getLayoutPathStatic(): string
    {
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        $layoutPath = "domains.{$prefix}.layout";

        if (view()->exists($layoutPath)) {
            return $layoutPath;
        }

        return 'domains.pottydirect.layout';
    }
}
