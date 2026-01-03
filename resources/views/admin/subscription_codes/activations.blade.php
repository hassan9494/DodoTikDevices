@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('Activation History') }}</h1>
        <a href="{{ route('admin.subscription-codes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to Codes') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Activated At') }}</th>
                            <th>{{ __('Expires At') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($activations as $activation)
                        <tr>
                            <td>{{ $activation->user->name }}<br><span class="text-muted small">{{ $activation->user->email }}</span></td>
                            <td class="font-weight-bold">{{ $activation->subscriptionCode->code }}</td>
                            <td>{{ $activation->activated_at?->format('Y-m-d H:i') ?? __('N/A') }}</td>
                            <td>{{ $activation->expires_at?->format('Y-m-d H:i') ?? __('N/A') }}</td>
                            <td>
                                @if ($activation->expires_at && $activation->expires_at->isFuture())
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Expired') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">{{ __('No activations found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $activations->links() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>
@endpush
