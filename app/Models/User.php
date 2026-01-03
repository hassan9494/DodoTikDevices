<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SubscriptionActivation;

/**
 * @method static orderBy(string $string, string $string1)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'phone',
        'is_active',

    ];

    protected $guarded=[];
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class,'user_id');
    }

    public function subscriptionActivations()
    {
        return $this->hasMany(SubscriptionActivation::class);
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->role === 'Administrator') {
            return true;
        }

        return $this->subscription_expires_at !== null && $this->subscription_expires_at->isFuture();
    }
}
