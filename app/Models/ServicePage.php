<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePage extends Model
{
    protected $fillable = [
        'domain_id', 'city_id', 'service_type', 'slug', 'h1_title',
        'meta_title', 'meta_description', 'content', 'content_html',
        'phone_number', 'canonical_url', 'schema_markup',
        'word_count', 'views', 'calls_generated', 'seo_score',
        'is_published', 'published_at', 'images',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'seo_score' => 'float',
        'seo_details' => 'array',
        'images' => 'array',
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

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        if ($this->domain && $this->domain->primary_service) {
            return $this->domain->getServiceTypeLabel($this->service_type);
        }

        return self::SERVICE_TYPES[$this->service_type] ?? ucfirst($this->service_type);
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

        return $this->city->active_phone ?? domain_phone_display();
    }

    public function getPhoneRawAttribute(): string
    {
        if ($this->phone_number) {
            return preg_replace('/[^0-9+]/', '', $this->phone_number);
        }

        return $this->city->active_phone_raw ?? domain_phone_raw();
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
        $details = [];

        // Get primary keywords from service type
        $serviceKeywords = $this->getServiceKeywords();
        $primaryKeyword = $serviceKeywords['primary'] ?? '';
        $cityName = optional($this->city)->name ?? '';
        $stateCode = optional(optional($this->city)->state)->code ?? '';

        // 1. TITLE TAG (15 points max)
        $titleLength = strlen($this->meta_title ?? '');
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 8;
            $details['title_length'] = 'good';
        } elseif ($titleLength > 0) {
            $score += 4;
            $details['title_length'] = 'needs_improvement';
        }

        // Title contains primary keyword + location
        $titleContent = strtolower($this->meta_title ?? '');
        if (str_contains($titleContent, strtolower($primaryKeyword)) &&
            (str_contains($titleContent, strtolower($cityName)) || str_contains($titleContent, strtolower($stateCode)))) {
            $score += 7;
            $details['title_keywords'] = 'good';
        }

        // 2. META DESCRIPTION (12 points max)
        $descLength = strlen($this->meta_description ?? '');
        if ($descLength >= 120 && $descLength <= 160) {
            $score += 7;
            $details['description_length'] = 'good';
        } elseif ($descLength > 0) {
            $score += 3;
            $details['description_length'] = 'needs_improvement';
        }

        // Description has CTA
        $ctaWords = ['call', 'get', 'free', 'quote', 'today', 'contact'];
        $descLower = strtolower($this->meta_description ?? '');
        $hasCta = false;
        foreach ($ctaWords as $word) {
            if (str_contains($descLower, $word)) {
                $hasCta = true;
                break;
            }
        }
        if ($hasCta) {
            $score += 5;
            $details['description_cta'] = 'good';
        }

        // 3. H1 TAG (10 points max)
        if (! empty($this->h1_title)) {
            $h1Content = strtolower($this->h1_title);
            if (str_contains($h1Content, strtolower($primaryKeyword))) {
                $score += 5;
            }
            if (str_contains($h1Content, strtolower($cityName))) {
                $score += 5;
            }
        }

        // 4. CONTENT LENGTH (15 points max)
        $wordCount = $this->word_count ?? 0;
        if ($wordCount >= 1500) {
            $score += 15;
            $details['word_count'] = 'excellent';
        } elseif ($wordCount >= 1000) {
            $score += 10;
            $details['word_count'] = 'good';
        } elseif ($wordCount >= 500) {
            $score += 5;
            $details['word_count'] = 'needs_improvement';
        }

        // 5. CONTENT QUALITY (15 points max)
        $content = strtolower(strip_tags($this->content ?? ''));

        // Keyword in first 100 words
        $firstWords = implode(' ', array_slice(explode(' ', $content), 0, 100));
        if (str_contains($firstWords, strtolower($primaryKeyword))) {
            $score += 5;
            $details['keyword_placement'] = 'good';
        }

        // Keyword density (1-2% ideal) - check for single word from keyword
        if (! empty($primaryKeyword)) {
            $keywordWords = explode(' ', $primaryKeyword);
            $mainWord = end($keywordWords);
            $keywordCount = substr_count($content, $mainWord);
            $keywordDensity = ($wordCount > 0) ? ($keywordCount / $wordCount) * 100 : 0;

            if ($keywordDensity >= 1 && $keywordDensity <= 2) {
                $score += 5;
                $details['keyword_density'] = 'optimal';
            } elseif ($keywordDensity > 0 && $keywordDensity < 1) {
                $score += 3;
                $details['keyword_density'] = 'low';
            }
        }

        // City name mentions
        $cityMentions = substr_count($content, strtolower($cityName));
        if ($cityMentions >= 5) {
            $score += 5;
            $details['city_mentions'] = 'good';
        } elseif ($cityMentions >= 3) {
            $score += 3;
        }

        // 6. SCHEMA MARKUP (10 points max)
        $schema = $this->schema_markup;
        if (! empty($schema)) {
            if (isset($schema['@type']) && $schema['@type'] === 'LocalBusiness') {
                $score += 5;
            }
            if (isset($schema['aggregateRating'])) {
                $score += 3;
            }
            if (isset($schema['hasOfferCatalog'])) {
                $score += 2;
            }
        }

        // 7. TECHNICAL SEO (13 points max)
        // Canonical URL
        if (! empty($this->canonical_url)) {
            $score += 4;
            $details['canonical'] = 'present';
        }

        // URL structure (slug contains city name)
        $slug = strtolower($this->slug ?? '');
        if (str_contains($slug, strtolower($cityName))) {
            $score += 4;
            $details['url_structure'] = 'good';
        }

        // Has phone number
        if (! empty($this->phone_number)) {
            $score += 5;
            $details['phone'] = 'present';
        }

        // 8. INTERNAL LINKS (5 points max)
        $internalLinkCount = substr_count($this->content ?? '', 'href="/');
        if ($internalLinkCount >= 3) {
            $score += 5;
        } elseif ($internalLinkCount >= 1) {
            $score += 3;
        }

        // 9. HEADING STRUCTURE (5 points max)
        $h2Count = substr_count(strtolower($this->content ?? ''), '<h2');
        $h3Count = substr_count(strtolower($this->content ?? ''), '<h3');
        if ($h2Count >= 3) {
            $score += 3;
        }
        if ($h3Count >= 2) {
            $score += 2;
        }

        // 10. IMAGES & MEDIA (8 points max)
        $imgCount = preg_match_all('/<img[^>]+>/i', $this->content ?? '', $matches);
        if ($imgCount > 0) {
            $altCount = preg_match_all('/alt=["\']([^"\']+)["\']/i', implode(' ', $matches[0] ?? []), $altMatches);
            if ($altCount >= $imgCount * 0.7) {
                $score += 5;
                $details['images'] = 'good';
            } elseif ($altCount > 0) {
                $score += 3;
            }
        }

        // 11. OUTBOUND LINKS (4 points max)
        $outboundCount = preg_match_all('/href=["\']https?:\/\/(?!'.preg_quote(parse_url(config('app.url'), PHP_URL_HOST), '/').')/i', $this->content ?? '', $outboundMatches);
        if ($outboundCount >= 2) {
            $score += 4;
        } elseif ($outboundCount >= 1) {
            $score += 2;
        }

        // Store detailed breakdown
        $this->update([
            'seo_score' => min($score, 100),
            'seo_details' => $details,
        ]);

        return min($score, 100);
    }

    private function getServiceKeywords(): array
    {
        $keywords = [
            'general' => ['primary' => 'porta potty rental', 'secondary' => 'portable toilet'],
            'construction' => ['primary' => 'construction site portable toilets', 'secondary' => 'job site toilets'],
            'wedding' => ['primary' => 'wedding restroom rentals', 'secondary' => 'wedding portable toilets'],
            'event' => ['primary' => 'event portable toilet rental', 'secondary' => 'festival restroom'],
            'luxury' => ['primary' => 'luxury restroom trailer', 'secondary' => 'VIP restroom trailer'],
            'party' => ['primary' => 'party porta potty rental', 'secondary' => 'party portable toilets'],
            'emergency' => ['primary' => 'emergency portable toilet', 'secondary' => '24/7 portable toilet'],
            'residential' => ['primary' => 'residential porta potty', 'secondary' => 'home renovation toilet'],
        ];

        return $keywords[$this->service_type] ?? ['primary' => 'porta potty rental', 'secondary' => 'portable toilet'];
    }
}
