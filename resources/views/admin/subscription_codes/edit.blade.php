@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('Edit Subscription Code') }}</h1>
        <a href="{{ route('admin.subscription-codes.index') }}" class="btn btn-light">{{ __('Back') }}</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subscription-codes.update', $subscriptionCode) }}">
                @csrf
                @method('PUT')

                @include('admin.subscription_codes._form', [
                    'subscriptionCode' => $subscriptionCode,
                    'submitLabel' => __('Update Code'),
                ])
            </form>
        </div>
    </div>
@endsection
