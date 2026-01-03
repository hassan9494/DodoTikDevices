@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('Create Subscription Code') }}</h1>
        <a href="{{ route('admin.subscription-codes.index') }}" class="btn btn-light">{{ __('Back') }}</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.subscription-codes.store') }}" method="POST">
                @include('admin.subscription_codes._form', [
                    'subscriptionCode' => $subscriptionCode,
                    'submitLabel' => __('Create'),
                ])
            </form>
        </div>
    </div>
@endsection
