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
    ];

    protected $casts = [
        'status' => 'boolean',
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
