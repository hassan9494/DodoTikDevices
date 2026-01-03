<?php

namespace App\Services;

use App\Models\SubscriptionActivation;
use App\Models\SubscriptionCode;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class SubscriptionManager
{
    public function __construct(private readonly DatabaseManager $db)
    {
    }

    public function redeem(User $user, string $code, Request $request): SubscriptionActivation
    {
        if ($user->role === 'Administrator') {
            throw ValidationException::withMessages([
                'code' => __('Administrators do not require subscriptions.'),
            ]);
        }

        $normalized = strtoupper(trim($code));

        return $this->db->transaction(function () use ($user, $normalized, $request) {
            /** @var SubscriptionCode|null $subscriptionCode */
            $subscriptionCode = SubscriptionCode::query()
                ->whereRaw('UPPER(code) = ?', [$normalized])
                ->lockForUpdate()
                ->first();

            if (!$subscriptionCode) {
                throw ValidationException::withMessages([
                    'code' => __('The subscription code is invalid.'),
                ]);
            }

            if (!$subscriptionCode->isCurrentlyActive()) {
                throw ValidationException::withMessages([
                    'code' => __('This subscription code is not currently active.'),
                ]);
            }

            if (!$subscriptionCode->hasRemainingUses()) {
                throw ValidationException::withMessages([
                    'code' => __('This subscription code has been fully redeemed.'),
                ]);
            }

            $now = Carbon::now();
            $baseDate = $user->subscription_expires_at && $user->subscription_expires_at->greaterThan($now)
                ? $user->subscription_expires_at
                : $now;

            $expiresAt = $baseDate->copy()->addDays($subscriptionCode->duration_days);

            $activation = SubscriptionActivation::query()->create([
                'user_id' => $user->id,
                'subscription_code_id' => $subscriptionCode->id,
                'activated_at' => $now,
                'expires_at' => $expiresAt,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->header('User-Agent'),
            ]);

            $subscriptionCode->incrementRedemptions();

            $user->forceFill([
                'subscription_expires_at' => $expiresAt,
            ])->save();

            return $activation;
        });
    }

    public function grant(User $user, Carbon $expiresAt, ?string $notes = null, ?SubscriptionCode $code = null): SubscriptionActivation
    {
        return $this->db->transaction(function () use ($user, $expiresAt, $notes, $code) {
            $activation = SubscriptionActivation::query()->create([
                'user_id' => $user->id,
                'subscription_code_id' => $code?->id,
                'activated_at' => Carbon::now(),
                'expires_at' => $expiresAt,
                'notes' => $notes,
            ]);

            $user->forceFill([
                'subscription_expires_at' => $expiresAt,
            ])->save();

            return $activation;
        });
    }
}
