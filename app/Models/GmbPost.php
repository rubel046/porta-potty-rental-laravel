<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GmbPost extends Model
{
    protected $table = 'gmb_posts';

    protected $fillable = [
        'gmb_account_id',
        'type',
        'content',
        'external_id',
        'blog_post_id',
        'status',
        'response_data',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function gmbAccount(): BelongsTo
    {
        return $this->belongsTo(GmbAccount::class);
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
