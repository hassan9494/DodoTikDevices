<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if (!$user->is_active) {
            Auth::guard('web')->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('auth.inactive'),
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->route('login')->withErrors([
                'email' => __('auth.inactive'),
            ]);
        }

        if ($user->role === 'Administrator') {
            return $next($request);
        }

        if (!$user->subscription_expires_at || $user->subscription_expires_at->isPast()) {
            if ($request->routeIs('subscription.prompt', 'subscription.redeem')) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('An active subscription is required for this action.'),
                ], 402);
            }

            return redirect()->route('subscription.prompt')
                ->withErrors(__('Your subscription has expired. Please redeem a new code.'));
        }

        return $next($request);
    }
}
