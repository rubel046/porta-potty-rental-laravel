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

    const SERVICE_TYPES = [
        self::TYPE_GENERAL => 'General Porta Potty Rental',
        self::TYPE_CONSTRUCTION => 'Construction Site Rental',
        self::TYPE_WEDDING => 'Wedding Rental',
        self::TYPE_EVENT => 'Event Rental',
        self::TYPE_LUXURY => 'Luxury Restroom Trailer',
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
        $type = self::SERVICE_TYPES[$this->service_type] ?? 'Porta Potty Rental';

        return "{$type} in {$city->name}, {$city->state->code} | Same-Day Delivery";
    }

    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        $city = $this->city;

        return "Need portable toilet rental in {$city->name}? We offer same-day delivery of clean, affordable porta potties for construction sites, events & weddings. Call {$city->name} for a free quote!";
    }

    public function getH1TitleAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        $city = $this->city;
        $type = self::SERVICE_TYPES[$this->service_type] ?? 'Porta Potty Rental';

        return "{$type} in {$city->name}, {$city->state->code}";
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

        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => "Porta Potty Rental {$city->name}",
            'description' => $this->meta_description,
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
        ];
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
