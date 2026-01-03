@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('Subscription Codes') }}</h1>
        <a href="{{ route('admin.subscription-codes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> {{ __('Create Code') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Duration (days)') }}</th>
                        <th>{{ __('Usage') }}</th>
                        <th>{{ __('Window') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($codes as $code)
                        <tr>
                            <td class="font-weight-bold">{{ $code->code }}</td>
                            <td>{{ $code->duration_days }}</td>
                            <td>{{ $code->times_redeemed }} / {{ $code->max_uses }}</td>
                            <td>
                                <div class="small">
                                    <div>{{ __('Start:') }} {{ optional($code->starts_at)->format('Y-m-d H:i') ?? __('Anytime') }}</div>
                                    <div>{{ __('End:') }} {{ optional($code->ends_at)->format('Y-m-d H:i') ?? __('No expiry') }}</div>
                                </div>
                            </td>
                            <td>
                                @if($code->is_active)
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.subscription-codes.edit', $code) }}" class="btn btn-sm btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subscription-codes.toggle', $code) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-pass">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.subscription-codes.destroy', $code) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">{{ __('No subscription codes found.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $codes->links() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>
@endpush
