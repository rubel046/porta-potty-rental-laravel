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
        'indexed_at', 'indexing_requested',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'seo_score' => 'float',
        'seo_details' => 'array',
        'images' => 'array',
        'indexed_at' => 'datetime',
        'indexing_requested' => 'boolean',
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

    const TYPE_PORTABLE = 'portable';

    // SERVICE_TYPES is now dynamic - use getServiceTypes() method

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
        $domain = $this->domain ?? Domain::current() ?? Domain::first();

        if ($domain && $domain->primary_service) {
            return $domain->getServiceTypeLabel($this->service_type);
        }

        // Fallback labels (should not be needed if domain is configured)
        $fallbackLabels = [
            self::TYPE_GENERAL => 'General Service Rental',
            self::TYPE_CONSTRUCTION => 'Construction Site Rental',
            self::TYPE_WEDDING => 'Wedding Rental',
            self::TYPE_EVENT => 'Event Rental',
            self::TYPE_LUXURY => 'Luxury Restroom Trailer',
            self::TYPE_PARTY => 'Party Rental',
            self::TYPE_EMERGENCY => 'Emergency Rental',
            self::TYPE_RESIDENTIAL => 'Residential Rental',
            self::TYPE_PORTABLE => 'Portable Rental',
        ];

        return $fallbackLabels[$this->service_type] ?? ucfirst($this->service_type).' Rental';
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
            // Replace {{PHONE_LINK}} with plain text phone number (not HTML link)
            return str_replace('{{PHONE_LINK}}', domain_phone_display(), $this->meta_description);
        }

        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($this->service_type) : 'Service';
        $cityName = $this->city->name;
        $stateCode = $this->city->state->code;

        $description = match ($this->service_type) {
            self::TYPE_CONSTRUCTION => "{$serviceLabel} in {$cityName}, {$stateCode}. Same-day delivery available.",
            self::TYPE_WEDDING => "{$serviceLabel} in {$cityName}, {$stateCode}. Professional service available.",
            self::TYPE_EVENT => "{$serviceLabel} in {$cityName}, {$stateCode}. Multiple options available.",
            self::TYPE_LUXURY => "{$serviceLabel} in {$cityName}, {$stateCode}. Premium service available.",
            default => "{$serviceLabel} in {$cityName}, {$stateCode}. Same-day delivery available.",
        };

        return $description;
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
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : 'Service';

        $serviceNames = [
            'general' => "{$serviceLabel} {$city->name}",
            'construction' => "Construction Site {$serviceLabel} {$city->name}",
            'wedding' => "Wedding {$serviceLabel} {$city->name}",
            'event' => "Event {$serviceLabel} {$city->name}",
            'luxury' => "Luxury {$serviceLabel} Trailer {$city->name}",
            'party' => "Party {$serviceLabel} {$city->name}",
            'emergency' => "Emergency {$serviceLabel} {$city->name}",
            'residential' => "Residential {$serviceLabel} {$city->name}",
        ];

        $descriptions = [
            'general' => "Professional, reliable {$serviceLabel} in {$city->name}, {$state->code}. Same-day delivery available.",
            'construction' => "Professional {$serviceLabel} for construction sites in {$city->name}, {$state->code}. Weekly servicing, same-day delivery available.",
            'wedding' => "Elegant {$serviceLabel} for weddings in {$city->name}, {$state->code}. Quality service for your special day.",
            'event' => "{$serviceLabel} for events in {$city->name}, {$state->code}. Festivals, parties, corporate events. Multiple options available.",
            'luxury' => "Luxury {$serviceLabel} trailer rental in {$city->name}, {$state->code}. Premium facilities for VIP events.",
            'party' => "{$serviceLabel} for parties in {$city->name}, {$state->code}. Perfect for celebrations and gatherings.",
            'emergency' => "Emergency {$serviceLabel} in {$city->name}, {$state->code}. Fast same-day delivery when you need it most.",
            'residential' => "{$serviceLabel} for residential projects in {$city->name}, {$state->code}. Home renovation and DIY projects.",
        ];

        // Replace {{PHONE_LINK}} in description if present
        $description = str_replace('{{PHONE_LINK}}', domain_phone_display(), $descriptions[$serviceType] ?? $descriptions['general']);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $serviceNames[$serviceType] ?? $serviceNames['general'],
            'description' => $description,
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

        if (in_array($serviceType, ['wedding', 'event'])) {
            $schema['event'] = [
                '@type' => 'Event',
                'name' => "{$serviceNames[$serviceType]} - {$city->name}",
                'description' => $descriptions[$serviceType],
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'eventStatus' => 'https://schema.org/EventScheduled',
                'location' => [
                    '@type' => 'Place',
                    'name' => "{$city->name} Area",
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $city->name,
                        'addressRegion' => $state->code,
                        'addressCountry' => 'US',
                    ],
                ],
                'provider' => [
                    '@type' => 'LocalBusiness',
                    'name' => $serviceNames[$serviceType],
                    'telephone' => $this->phone_raw,
                ],
            ];
        }

        return $schema;
    }

    protected function getServiceOfferings(string $serviceType): array
    {
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : 'Service';

        $offerings = [
            'general' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Standard {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Deluxe {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Accessible Unit']],
            ],
            'construction' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Construction Site {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Compliant Units']],
            ],
            'wedding' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Premium {$serviceLabel} Trailer"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Deluxe {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Wedding {$serviceLabel} Package"]],
            ],
            'event' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Event {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Accessible Unit']],
            ],
            'luxury' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Executive Series {$serviceLabel} Trailer"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Ambassador Series {$serviceLabel} Trailer"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Presidential Series {$serviceLabel} Trailer"]],
            ],
            'party' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Party {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe Party Unit']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Hand Wash Station']],
            ],
            'emergency' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Emergency {$serviceLabel}"]],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Same-Day Emergency Delivery']],
            ],
            'residential' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Residential {$serviceLabel}"]],
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
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceType = $this->service_type;

        // Get domain-specific keywords
        $primaryKeyword = $domain->primary_keyword ?? 'service rental';
        $secondaryKeywords = $domain->getSecondaryKeywords();

        // Build keywords based on service type
        $typeSuffixes = [
            'general' => '',
            'construction' => 'construction site',
            'wedding' => 'wedding',
            'event' => 'event',
            'luxury' => 'luxury',
            'party' => 'party',
            'emergency' => 'emergency',
            'residential' => 'residential',
        ];

        $suffix = $typeSuffixes[$serviceType] ?? '';
        $primary = $suffix ? "{$suffix} {$primaryKeyword}" : $primaryKeyword;

        // Build secondary keywords
        $secondary = $primaryKeyword;
        if (! empty($suffix)) {
            $secondary .= ", {$suffix}";
        }
        if (! empty($secondaryKeywords)) {
            $secondary .= ', '.implode(', ', array_slice($secondaryKeywords, 0, 3));
        }

        return [
            'primary' => $primary,
            'secondary' => $secondary,
        ];
    }
}
