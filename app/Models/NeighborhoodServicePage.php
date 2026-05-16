<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NeighborhoodServicePage extends Model
{
    protected $table = 'neighborhood_service_pages';

    protected $fillable = [
        'neighborhood_id', 'domain_id', 'service_type', 'slug',
        'h1_title', 'meta_title', 'meta_description', 'content', 'content_html',
        'images', 'word_count', 'is_published', 'published_at',
        'generation_status', 'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'generated_at' => 'datetime',
        ];
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    public function getUrlAttribute(): string
    {
        return url("neighborhoods/{$this->slug}");
    }
}
