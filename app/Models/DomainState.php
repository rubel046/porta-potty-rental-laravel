<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainState extends Model
{
    protected $table = 'domain_states';

    protected $fillable = [
        'domain_id',
        'state_id',
        'status',
        'h1_title',
        'meta_title',
        'meta_description',
        'content',
        'images',
        'word_count',
        'seo_score',
        'generation_status',
        'generation_error',
        'generated_at',
        'indexed_at',
        'indexing_requested',
    ];

    protected $casts = [
        'status' => 'boolean',
        'images' => 'array',
        'seo_score' => 'float',
        'indexed_at' => 'datetime',
        'indexing_requested' => 'boolean',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
