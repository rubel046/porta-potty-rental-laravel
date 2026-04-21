<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndexingUrl extends Model
{
    protected $fillable = [
        'url',
        'type',
        'reference_type',
        'reference_id',
        'indexed',
        'indexed_at',
        'requested_at',
        'status',
        'error_message',
    ];

    protected $casts = [
        'indexed' => 'boolean',
        'indexed_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

    public function reference()
    {
        return $this->morphTo();
    }

    public function markAsSubmitted(): void
    {
        $this->update([
            'status' => 'submitted',
            'requested_at' => now(),
        ]);
    }

    public function markAsIndexed(): void
    {
        $this->update([
            'indexed' => true,
            'indexed_at' => now(),
            'status' => 'indexed',
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    public static function createFromServicePage(ServicePage $page): self
    {
        return self::updateOrCreate(
            ['url' => url($page->slug)],
            [
                'type' => 'service',
                'reference_type' => ServicePage::class,
                'reference_id' => $page->id,
                'status' => 'pending',
            ]
        );
    }

    public static function createFromDomainState(DomainState $state): self
    {
        return self::updateOrCreate(
            ['url' => url('state/'.$state->state->slug)],
            [
                'type' => 'state',
                'reference_type' => DomainState::class,
                'reference_id' => $state->id,
                'status' => 'pending',
            ]
        );
    }

    public static function createFromBlogPost(BlogPost $post): self
    {
        return self::updateOrCreate(
            ['url' => url('blog/'.$post->slug)],
            [
                'type' => 'blog',
                'reference_type' => BlogPost::class,
                'reference_id' => $post->id,
                'status' => 'pending',
            ]
        );
    }
}
