<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionCodeRequest;
use App\Http\Requests\UpdateSubscriptionCodeRequest;
use App\Models\SubscriptionActivation;
use App\Models\SubscriptionCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:isAdmin');
    }

    public function index(): View
    {
        $codes = SubscriptionCode::query()
            ->withCount('activations')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.subscription_codes.index', compact('codes'));
    }

    public function create(): View
    {
        return view('admin.subscription_codes.create', [
            'subscriptionCode' => new SubscriptionCode(),
        ]);
    }

    public function store(StoreSubscriptionCodeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = $request->user()->id;

        SubscriptionCode::create($data);

        return redirect()
            ->route('admin.subscription-codes.index')
            ->with('success', __('Subscription code created successfully.'));
    }

    public function edit(SubscriptionCode $subscriptionCode): View
    {
        return view('admin.subscription_codes.edit', compact('subscriptionCode'));
    }

    public function activations(): View
    {
        $activations = SubscriptionActivation::query()
            ->with(['user', 'subscriptionCode'])
            ->newestFirst()
            ->paginate(25);

        return view('admin.subscription_codes.activations', compact('activations'));
    }

    public function update(UpdateSubscriptionCodeRequest $request, SubscriptionCode $subscriptionCode): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        $subscriptionCode->update($payload);

        return redirect()
            ->route('admin.subscription-codes.index')
            ->with('success', __('Subscription code updated successfully.'));
    }

    public function destroy(SubscriptionCode $subscriptionCode): RedirectResponse
    {
        if ($subscriptionCode->activations()->exists()) {
            return redirect()
                ->route('admin.subscription-codes.index')
                ->with('error', __('Cannot delete a code that has activations. Disable it instead.'));
        }

        $subscriptionCode->delete();

        return redirect()
            ->route('admin.subscription-codes.index')
            ->with('success', __('Subscription code deleted successfully.'));
    }

    public function toggle(SubscriptionCode $subscriptionCode): RedirectResponse
    {
        $subscriptionCode->is_active = ! $subscriptionCode->is_active;
        $subscriptionCode->save();

        return redirect()
            ->route('admin.subscription-codes.index')
            ->with('success', __('Subscription code status updated.'));
    }
}
