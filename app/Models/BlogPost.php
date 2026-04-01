<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'blog_category_id', 'city_id', 'title', 'slug', 'excerpt',
        'content', 'content_html', 'featured_image',
        'meta_title', 'meta_description', 'focus_keyword',
        'secondary_keywords', 'schema_markup',
        'word_count', 'views', 'reading_time',
        'is_published', 'is_featured', 'published_at',
    ];

    protected $casts = [
        'secondary_keywords' => 'array',
        'schema_markup' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getUrlAttribute(): string
    {
        return url("blog/{$this->slug}");
    }

    public function getReadingTimeTextAttribute(): string
    {
        return "{$this->reading_time} min read";
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            $post->word_count = str_word_count(strip_tags($post->content));
            $post->reading_time = max(1, ceil($post->word_count / 200));

            if (! $post->excerpt) {
                $post->excerpt = Str::limit(strip_tags($post->content), 200);
            }

            if (! $post->meta_title) {
                $post->meta_title = Str::limit($post->title, 60);
            }
        });
    }
}
