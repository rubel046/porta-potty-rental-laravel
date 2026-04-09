<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainCity extends Model
{
    protected $table = 'domain_cities';

    protected $fillable = [
        'domain_id',
        'city_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
