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

    // Service types are now dynamic - retrieved from Domain model
    // Use Domain::current()->getServiceTypes() to get allowed types

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

        if ($domain) {
            return $domain->getServiceTypeLabel($this->service_type);
        }

        return ucfirst(str_replace('-', ' ', $this->service_type));
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
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($this->service_type) : 'Service Rental';

        // Phone in the title consistently lifts SERP CTR for local-service queries.
        // Target length 55-60 chars — Google truncates ~60 on desktop.
        $phone = $this->phone_display;
        $base = "{$serviceLabel} {$city->name} {$state} · Same-Day · {$phone}";

        // If that's too long, drop "Same-Day" before dropping the phone.
        if (strlen($base) > 62) {
            $base = "{$serviceLabel} {$city->name} {$state} · {$phone}";
        }

        return $base;
    }

    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return str_replace('{{PHONE_LINK}}', domain_phone_display(), $this->meta_description);
        }

        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($this->service_type) : 'Service';
        $cityName = $this->city->name;
        $stateCode = $this->city->state->code;

        // Price hint only when you've published real, verified price ranges.
        // See config/service_pricing.php for the master switch.
        $pricePhrase = '';
        if (config('service_pricing.enabled', false)) {
            $range = config('service_pricing.ranges.'.$this->service_type)
                ?? config('service_pricing.fallback');
            if ($range) {
                $pricePhrase = "From \${$range['low']}/day. ";
            }
        }

        $phone = domain_phone_display();

        // Target 140-160 chars — fills SERP width without truncation.
        $desc = "{$serviceLabel} in {$cityName}, {$stateCode}. {$pricePhrase}Same-day delivery, weekly service, no hidden fees. Call {$phone} — answered in 30 seconds.";

        // Hard cap at 160, cut cleanly at last word
        if (strlen($desc) > 160) {
            $desc = rtrim(substr($desc, 0, 157)).'…';
            $desc = preg_replace('/\s\S*?…$/', '…', $desc);
        }

        return $desc;
    }

    public function getH1TitleAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        $city = $this->city;
        $state = $city->state->code;
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($this->service_type) : 'Service Rental';

        return "{$serviceLabel} in {$city->name}, {$state} | Same-Day Delivery";
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

        $description = "Professional, reliable {$serviceLabel} in {$city->name}, {$state->code}. Same-day delivery available.";
        $description = str_replace('{{PHONE_LINK}}', domain_phone_display(), $description);

        $opensAt = config('contact.hours_open', '07:00');
        $closesAt = config('contact.hours_close', '20:00');

        // Precise areaServed: GeoCircle ranks better in local pack than a City label
        // when the service business travels to customers (vs. customers visiting a storefront).
        $areaServed = $city->latitude && $city->longitude ? [
            '@type' => 'GeoCircle',
            'geoMidpoint' => [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $city->latitude,
                'longitude' => (float) $city->longitude,
            ],
            'geoRadius' => '40000', // 40km — typical service-area truck range
        ] : [
            '@type' => 'City',
            'name' => $city->name,
        ];

        return [
            '@context' => 'https://schema.org',
            '@type' => ['LocalBusiness', 'HomeAndConstructionBusiness'],
            'name' => "{$serviceLabel} {$city->name}",
            'description' => $description,
            'telephone' => $this->phone_raw,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $city->name,
                'addressRegion' => $state->code,
                'addressCountry' => 'US',
            ],
            'areaServed' => $areaServed,
            'priceRange' => '$$',
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens' => $opensAt,
                'closes' => $closesAt,
            ]],
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => $this->phone_raw,
                'contactType' => 'customer service',
                'areaServed' => 'US',
                'availableLanguage' => ['English'],
                'hoursAvailable' => [[
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => $opensAt,
                    'closes' => $closesAt,
                ]],
            ]],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => "{$serviceLabel} Services - {$city->name}",
                'itemListElement' => $this->getServiceOfferings(),
            ],
        ];
    }

    protected function getServiceOfferings(): array
    {
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($this->service_type) : 'Service';

        return [
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Standard {$serviceLabel}"]],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => "Deluxe {$serviceLabel}"]],
            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Accessible Unit']],
        ];
    }

    public function calculateSeoScore(): float
    {
        $score = 0;
        $details = [];

        $serviceKeywords = $this->getServiceKeywords();
        $primaryKeyword = $serviceKeywords['primary'] ?? '';
        $cityName = optional($this->city)->name ?? '';
        $stateCode = optional(optional($this->city)->state)->code ?? '';

        // 1. TITLE TAG
        $titleLength = strlen($this->meta_title ?? '');
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 8;
            $details['title_length'] = 'good';
        } elseif ($titleLength > 0) {
            $score += 4;
            $details['title_length'] = 'needs_improvement';
        }

        $titleContent = strtolower($this->meta_title ?? '');
        if (str_contains($titleContent, strtolower($primaryKeyword)) &&
            (str_contains($titleContent, strtolower($cityName)) || str_contains($titleContent, strtolower($stateCode)))) {
            $score += 7;
            $details['title_keywords'] = 'good';
        }

        // 2. META DESCRIPTION
        $descLength = strlen($this->meta_description ?? '');
        if ($descLength >= 120 && $descLength <= 160) {
            $score += 7;
            $details['description_length'] = 'good';
        } elseif ($descLength > 0) {
            $score += 3;
            $details['description_length'] = 'needs_improvement';
        }

        $descLower = strtolower($this->meta_description ?? '');
        $hasCta = false;
        foreach (['call', 'get', 'free', 'quote', 'today', 'contact'] as $word) {
            if (str_contains($descLower, $word)) {
                $hasCta = true;
                break;
            }
        }
        if ($hasCta) {
            $score += 5;
            $details['description_cta'] = 'good';
        }

        // 3. H1 TAG
        if (! empty($this->h1_title)) {
            $h1Content = strtolower($this->h1_title);
            if (str_contains($h1Content, strtolower($primaryKeyword))) {
                $score += 5;
            }
            if (str_contains($h1Content, strtolower($cityName))) {
                $score += 5;
            }
        }

        // 4. CONTENT LENGTH
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

        // 5. CONTENT QUALITY
        $content = strtolower(strip_tags($this->content ?? ''));

        $firstWords = implode(' ', array_slice(explode(' ', $content), 0, 100));
        if (str_contains($firstWords, strtolower($primaryKeyword))) {
            $score += 5;
            $details['keyword_placement'] = 'good';
        }

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

        $cityMentions = substr_count($content, strtolower($cityName));
        if ($cityMentions >= 5) {
            $score += 5;
            $details['city_mentions'] = 'good';
        } elseif ($cityMentions >= 3) {
            $score += 3;
        }

        // 6. SCHEMA MARKUP
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

        // 7. TECHNICAL SEO
        if (! empty($this->canonical_url)) {
            $score += 4;
            $details['canonical'] = 'present';
        }

        $slug = strtolower($this->slug ?? '');
        if (str_contains($slug, strtolower($cityName))) {
            $score += 4;
            $details['url_structure'] = 'good';
        }

        if (! empty($this->phone_number)) {
            $score += 5;
            $details['phone'] = 'present';
        }

        // 8. INTERNAL LINKS
        $internalLinkCount = substr_count($this->content ?? '', 'href="/');
        if ($internalLinkCount >= 3) {
            $score += 5;
        } elseif ($internalLinkCount >= 1) {
            $score += 3;
        }

        // 9. HEADING STRUCTURE
        $h2Count = substr_count(strtolower($this->content ?? ''), '<h2');
        $h3Count = substr_count(strtolower($this->content ?? ''), '<h3');
        if ($h2Count >= 3) {
            $score += 3;
        }
        if ($h3Count >= 2) {
            $score += 2;
        }

        // 10. IMAGES & MEDIA
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

        // 11. OUTBOUND LINKS
        $host = parse_url(config('app.url'), PHP_URL_HOST);
        $outboundCount = preg_match_all('/href=["\']https?:\/\/(?!'.preg_quote($host, '/').')/i', $this->content ?? '', $outboundMatches);
        if ($outboundCount >= 2) {
            $score += 4;
        } elseif ($outboundCount >= 1) {
            $score += 2;
        }

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

        $primaryKeyword = $domain->primary_keyword ?? 'service rental';
        $secondaryKeywords = $domain->getSecondaryKeywords();
        $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : $serviceType;

        $primary = "{$serviceLabel} {$primaryKeyword}";

        $secondary = $primaryKeyword;
        if (! empty($secondaryKeywords)) {
            $secondary .= ', '.implode(', ', array_slice($secondaryKeywords, 0, 3));
        }

        return [
            'primary' => $primary,
            'secondary' => $secondary,
        ];
    }
}
