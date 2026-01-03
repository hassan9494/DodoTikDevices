<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserSubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionManager $subscriptionManager)
    {
        $this->middleware(['auth', 'verified']);
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        return view('subscription.redeem', [
            'user' => $user,
            'activations' => $user->subscriptionActivations()->orderByDesc('activated_at')->limit(10)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();

        $this->subscriptionManager->redeem($user, $validated['code'], $request);

        $user->refresh();

        return redirect()->route('subscription.prompt')->with(
            'success',
            __('Subscription activated successfully. Your access expires on :date', [
                'date' => optional($user->subscription_expires_at)->format('Y-m-d H:i'),
            ])
        );
    }
}
