@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Verify Your Email Address') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        {{ __('Before proceeding, please check your email for a verification link to activate your account.') }}
                    </p>

                    @if (session('status') === 'verification-link-sent' || session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        {{ __('Didn\'t receive the message? You can request another verification email below.') }}
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> {{ __('Resend Verification Email') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
