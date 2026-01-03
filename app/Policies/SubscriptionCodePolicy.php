<?php

namespace App\Policies;

use App\Models\SubscriptionCode;
use App\Models\User;

class SubscriptionCodePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'Administrator') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, SubscriptionCode $subscriptionCode): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, SubscriptionCode $subscriptionCode): bool
    {
        return false;
    }

    public function delete(User $user, SubscriptionCode $subscriptionCode): bool
    {
        return false;
    }
}
