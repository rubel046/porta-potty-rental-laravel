<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageQualityScore extends Model
{
    protected $fillable = [
        'service_page_id',
        'domain_id',
        'score',
        'grade',
        'word_count',
        'faq_count',
        'testimonial_count',
        'details',
        'scored_at',
    ];

    protected $casts = [
        'details' => 'array',
        'scored_at' => 'datetime',
    ];

    public function servicePage(): BelongsTo
    {
        return $this->belongsTo(ServicePage::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
