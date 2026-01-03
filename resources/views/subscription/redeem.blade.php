@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Subscription Status') }}</h5>
                </div>
                <div class="card-body">
                    @if($user->role === 'Administrator')
                        <p class="mb-0 text-success">{{ __('Administrators have unlimited access.') }}</p>
                    @elseif($user->subscription_expires_at && $user->subscription_expires_at->isFuture())
                        <p class="mb-1 text-success">{{ __('Active until :date', ['date' => $user->subscription_expires_at->format('Y-m-d H:i')]) }}</p>
                    @else
                        <p class="mb-1 text-danger">{{ __('Your subscription is inactive.') }}</p>
                    @endif

                    <p class="text-muted mb-0">{{ __('Redeem a subscription code below to continue managing devices.') }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Redeem Code') }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('subscription.redeem') }}">
                        @csrf
                        <div class="form-group">
                            <label for="code">{{ __('Subscription Code') }}</label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" placeholder="{{ __('Enter your code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Redeem') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Recent Activations') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Activated At') }}</th>
                                    <th>{{ __('Expires At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activations as $activation)
                                    <tr>
                                        <td>{{ optional($activation->subscriptionCode)->code ?? __('Manual') }}</td>
                                        <td>{{ $activation->activated_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $activation->expires_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">{{ __('No activations yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
