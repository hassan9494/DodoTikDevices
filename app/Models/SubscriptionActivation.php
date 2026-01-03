<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class SubscriptionActivation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_code_id',
        'activated_at',
        'expires_at',
        'ip_address',
        'user_agent',
        'notes',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionCode(): BelongsTo
    {
        return $this->belongsTo(SubscriptionCode::class);
    }

    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('activated_at');
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('expires_at', '>=', Carbon::now());
    }
}
