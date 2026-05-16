<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class GmbAccount extends Model
{
    protected $table = 'gmb_accounts';

    protected $fillable = [
        'domain_id',
        'account_name',
        'account_email',
        'location_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_active',
        'auto_post',
        'auto_reply_reviews',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'auto_post' => 'boolean',
            'auto_reply_reviews' => 'boolean',
            'token_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function encryptToken(?string $value): ?string
    {
        return $value ? Crypt::encryptString($value) : null;
    }

    public function decryptToken(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getDecryptedAccessToken(): ?string
    {
        return $this->decryptToken($this->access_token);
    }

    public function getDecryptedRefreshToken(): ?string
    {
        return $this->decryptToken($this->refresh_token);
    }
}
