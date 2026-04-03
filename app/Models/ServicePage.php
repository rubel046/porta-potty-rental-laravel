<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServicePage extends Model
{
    protected $fillable = [
        'city_id', 'service_type', 'slug', 'h1_title',
        'meta_title', 'meta_description', 'content', 'content_html',
        'phone_number', 'canonical_url', 'schema_markup',
        'word_count', 'views', 'calls_generated', 'seo_score',
        'is_published', 'published_at',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'seo_score' => 'float',
    ];

    // Service Types
    const TYPE_GENERAL = 'general';

    const TYPE_CONSTRUCTION = 'construction';

    const TYPE_WEDDING = 'wedding';

    const TYPE_EVENT = 'event';

    const TYPE_LUXURY = 'luxury';

    const TYPE_PARTY = 'party';

    const TYPE_EMERGENCY = 'emergency';

    const TYPE_RESIDENTIAL = 'residential';

    const SERVICE_TYPES = [
        self::TYPE_GENERAL => 'General Porta Potty Rental',
        self::TYPE_CONSTRUCTION => 'Construction Site Rental',
        self::TYPE_WEDDING => 'Wedding Rental',
        self::TYPE_EVENT => 'Event Rental',
        self::TYPE_LUXURY => 'Luxury Restroom Trailer',
        self::TYPE_PARTY => 'Party Rental',
        self::TYPE_EMERGENCY => 'Emergency Rental',
        self::TYPE_RESIDENTIAL => 'Residential Rental',
    ];

    // Relationships
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('service_type', $type);
    }

    // Accessors
    public function getPhoneDisplayAttribute(): string
    {
        if ($this->phone_number) {
            return $this->phone_number;
        }

        return $this->city->active_phone ?? phone_display();
    }

    public function getPhoneRawAttribute(): string
    {
        if ($this->phone_number) {
            return preg_replace('/[^0-9+]/', '', $this->phone_number);
        }

        return $this->city->active_phone_raw ?? phone_raw();
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }

    public function getSeoTitleAttribute(): string
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }

        $city = $this->city;
        $state = $city->state->code;
        $type = self::SERVICE_TYPES[$this->service_type] ?? 'Porta Potty Rental';

        return "{$type} in {$city->name}, {$state} | Same-Day Delivery | Potty Direct";
    }

    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        $city = $this->city;
        $typeLabel = match ($this->service_type) {
            self::TYPE_CONSTRUCTION => 'construction sites and job sites',
            self::TYPE_WEDDING => 'weddings and outdoor celebrations',
            self::TYPE_EVENT => 'events, festivals, and gatherings',
            self::TYPE_LUXURY => 'VIP events and upscale celebrations',
            default => 'construction sites, events, and weddings',
        };

        return "Need portable toilet rental in {$city->name}, {$city->state->code}? We offer same-day delivery of clean, affordable porta potties for {$typeLabel}. Call {$city->name} now for a free quote!";
    }

    public function getH1TitleAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        $city = $this->city;
        $state = $city->state->code;
        $type = self::SERVICE_TYPES[$this->service_type] ?? 'Porta Potty Rental';

        return "{$type} in {$city->name}, {$state} | Same-Day Delivery";
    }

    public function getUrlAttribute(): string
    {
        return url($this->slug);
    }

    // Methods
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function incrementCalls(): void
    {
        $this->increment('calls_generated');
    }

    public function generateSchemaMarkup(): array
    {
        $city = $this->city;
        $state = $city->state;
        $serviceType = $this->service_type;

        $serviceNames = [
            'general' => "Porta Potty Rental {$city->name}",
            'construction' => "Construction Site Porta Potty {$city->name}",
            'wedding' => "Wedding Restroom Rental {$city->name}",
            'event' => "Event Porta Potty Rental {$city->name}",
            'luxury' => "Luxury Restroom Trailer {$city->name}",
            'party' => "Party Porta Potty Rental {$city->name}",
            'emergency' => "Emergency Porta Potty {$city->name}",
            'residential' => "Residential Porta Potty {$city->name}",
        ];

        $descriptions = [
            'general' => "Clean, affordable portable toilet rental in {$city->name}, {$state->code}. Same-day delivery available for construction sites, events, and weddings.",
            'construction' => "OSHA-compliant portable toilet rental for construction sites in {$city->name}, {$state->code}. Weekly servicing, same-day delivery available.",
            'wedding' => "Elegant wedding restroom rental in {$city->name}, {$state->code}. Luxury trailers and deluxe units for your special day.",
            'event' => "Portable toilet rental for events in {$city->name}, {$state->code}. Festivals, parties, corporate events. Multiple unit packages available.",
            'luxury' => "Luxury restroom trailer rental in {$city->name}, {$state->code}. Climate-controlled, elegant facilities for VIP events and weddings.",
            'party' => "Party porta potty rental in {$city->name}, {$state->code}. Perfect for backyard parties, birthdays, and family reunions.",
            'emergency' => "Emergency portable toilet rental in {$city->name}, {$state->code}. Fast same-day delivery for plumbing failures and disaster relief.",
            'residential' => "Residential porta potty rental in {$city->name}, {$state->code}. Home renovation and DIY project portable toilets.",
        ];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $serviceNames[$serviceType] ?? $serviceNames['general'],
            'description' => $descriptions[$serviceType] ?? $descriptions['general'],
            'telephone' => $this->phone_raw,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $city->name,
                'addressRegion' => $state->code,
                'addressCountry' => 'US',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city->name,
            ],
            'priceRange' => '$$',
            'openingHours' => 'Mo-Sa 07:00-20:00',
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => "Portable Restroom Services - {$city->name}",
                'itemListElement' => $this->getServiceOfferings($serviceType),
            ],
        ];
    }

    protected function getServiceOfferings(string $serviceType): array
    {
        $offerings = [
            'general' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Standard Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe Flushable Unit']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'ADA Accessible Unit']],
            ],
            'construction' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Construction Site Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'OSHA Compliant Units']],
            ],
            'wedding' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Luxury Restroom Trailer']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe Flushable Unit']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Wedding Restroom Package']],
            ],
            'event' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Event Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'ADA Accessible Unit']],
            ],
            'luxury' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Executive Series Trailer']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Ambassador Series Trailer']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Presidential Series Trailer']],
            ],
            'party' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Party Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe Party Unit']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
            ],
            'emergency' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Emergency Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Same-Day Emergency Delivery']],
            ],
            'residential' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Residential Portable Toilet']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Home Project Sanitation']],
            ],
        ];

        return $offerings[$serviceType] ?? $offerings['general'];
    }

    public function calculateSeoScore(): float
    {
        $score = 0;

        // Word count check
        if ($this->word_count >= 1500) {
            $score += 20;
        } elseif ($this->word_count >= 1000) {
            $score += 10;
        }

        // Meta title check
        if (strlen($this->meta_title) >= 30 && strlen($this->meta_title) <= 60) {
            $score += 15;
        }

        // Meta description check
        if (strlen($this->meta_description) >= 120 && strlen($this->meta_description) <= 160) {
            $score += 15;
        }

        // H1 contains city name
        if (Str::contains($this->h1_title, $this->city->name)) {
            $score += 15;
        }

        // Content contains city name multiple times
        $cityMentions = substr_count(strtolower($this->content), strtolower($this->city->name));
        if ($cityMentions >= 5) {
            $score += 10;
        } elseif ($cityMentions >= 3) {
            $score += 5;
        }

        // Has phone number
        if ($this->phone_number) {
            $score += 10;
        }

        // Has schema markup
        if ($this->schema_markup) {
            $score += 15;
        }

        $this->update(['seo_score' => $score]);

        return $score;
    }
}
