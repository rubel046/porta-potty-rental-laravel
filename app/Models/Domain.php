<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Domain extends Model
{
    protected $fillable = [
        'domain',
        'email',
        'business_name',
        'website_url',
        'primary_keyword',
        'primary_service',
        'slug_prefix',
        'service_types',
        'service_labels',
        'secondary_keywords',
        'primary_color',
        'secondary_color',
        'twitter_handle',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'service_types' => 'array',
        'secondary_keywords' => 'array',
        'content_prompts' => 'array',
        'service_labels' => 'array',
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
        return $this->service_labels[$type] ?? ucfirst(str_replace('-', ' ', $type));
    }

    public function getServiceSlugPrefix(): string
    {
        // Use explicit slug_prefix if set, otherwise derive from primary_service
        if (! empty($this->slug_prefix)) {
            return $this->slug_prefix;
        }

        return $this->primary_service ? str_replace(' ', '-', $this->primary_service) : 'service';
    }

    public function getServiceSlugSuffix(): string
    {
        return $this->isRentalDomain() ? 'rental' : '';
    }

    public function isRentalDomain(): bool
    {
        $rentalIndicators = ['porta potty', 'portable', 'rental'];
        $primaryService = strtolower($this->primary_service ?? '');
        $prefix = strtolower($this->slug_prefix ?? '');

        foreach ($rentalIndicators as $indicator) {
            if (str_contains($primaryService, $indicator) || str_contains($prefix, $indicator)) {
                return true;
            }
        }

        return false;
    }

    public static function current(): ?self
    {
        if ($id = session('current_domain_id')) {
            return static::find($id);
        }

        $host = request()->getHost();

        if (app()->isLocal() && str_ends_with($host, '.test')) {
            $host = str_replace('.test', '.com', $host);
        }

        $cacheKey = "domain_id_{$host}";
        $domainId = cache($cacheKey);

        if ($domainId) {
            $domain = static::find($domainId);
            if ($domain) {
                return $domain;
            }
            cache()->forget($cacheKey);
        }

        $domain = static::where('domain', $host)->first();
        if ($domain) {
            try {
                cache()->put($cacheKey, $domain->id, now()->addHours(1));
            } catch (\Exception $e) {
                Log::warning('Domain cache write failed', ['host' => $host, 'error' => $e->getMessage()]);
            }
        }

        return $domain;
    }

    public static function setCurrent(self $domain): void
    {
        session(['current_domain_id' => $domain->id]);
        cache()->put("domain_{$domain->domain}", $domain, now()->addHours(1));
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
