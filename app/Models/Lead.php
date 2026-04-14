<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'zip',
        'service_type',
        'status',
        'notes',
        'source',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_CONTACTED = 'contacted';

    const STATUS_CONVERTED = 'converted';

    const STATUS_LOST = 'lost';
}
