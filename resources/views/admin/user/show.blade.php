@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('message.Profile Information') }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('message.Back') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('message.User Details') }}</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">{{ __('message.Name') }}</dt>
                        <dd class="col-sm-7">{{ $user->name }}</dd>

                        <dt class="col-sm-5 text-muted">{{ __('message.Username') }}</dt>
                        <dd class="col-sm-7">{{ $user->username }}</dd>

                        <dt class="col-sm-5 text-muted">{{ __('message.Email') }}</dt>
                        <dd class="col-sm-7">{{ $user->email }}</dd>

                        <dt class="col-sm-5 text-muted">{{ __('message.Phone') }}</dt>
                        <dd class="col-sm-7">{{ $user->phone }}</dd>

                        <dt class="col-sm-5 text-muted">{{ __('message.Role') }}</dt>
                        <dd class="col-sm-7">{{ $user->role }}</dd>

                        <dt class="col-sm-5 text-muted">{{ __('message.Subscription Status') }}</dt>
                        <dd class="col-sm-7">
                            @if($user->hasActiveSubscription())
                                <span class="badge badge-success">{{ __('message.Active') }}</span>
                                <div class="small text-muted mt-1">
                                    {{ __('message.Expires At') }}: {{ optional($user->subscription_expires_at)->format('Y-m-d H:i') }}
                                </div>
                            @else
                                <span class="badge badge-secondary">{{ __('message.Inactive') }}</span>
                                @if($user->subscription_expires_at)
                                    <div class="small text-muted mt-1">
                                        {{ __('message.Expired At') }}: {{ $user->subscription_expires_at->format('Y-m-d H:i') }}
                                    </div>
                                @endif
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('message.Associated Devices') }}</h6>
                    <span class="badge badge-info">{{ $devices->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="devicesTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('message.Name') }}</th>
                                <th>{{ __('message.device_id') }}</th>
                                <th>{{ __('message.type') }}</th>
                                <th>{{ __('message.Option') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($devices as $device)
                                <tr>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->device_id }}</td>
                                    <td>{{ optional($device->deviceType)->name ?? __('N/A') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.devices.show', $device->id) }}" class="btn btn-sm btn-edit">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.devices.edit', $device->id) }}" class="btn btn-sm btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">{{ __('message.No Devices In This Type') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('message.Activation History') }}</h6>
                    <span class="badge badge-info">{{ $activations->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="activationsTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('message.Code') }}</th>
                                <th>{{ __('message.Activated At') }}</th>
                                <th>{{ __('message.Expires At') }}</th>
                                <th>{{ __('message.Status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($activations as $activation)
                                <tr>
                                    <td>{{ $activation->subscriptionCode->code ?? __('message.N/A') }}</td>
                                    <td>{{ $activation->activated_at?->format('Y-m-d H:i') ?? __('message.N/A') }}</td>
                                    <td>{{ $activation->expires_at?->format('Y-m-d H:i') ?? __('message.N/A') }}</td>
                                    <td>
                                        @if ($activation->expires_at && $activation->expires_at->isFuture())
                                            <span class="badge badge-success">{{ __('message.Active') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('message.Inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">{{ __('message.No activations found.') }}</td>
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

@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(function () {
            $('#devicesTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                language: {
                    emptyTable: '{{ __('message.No Devices In This Type') }}'
                }
            });
            $('#activationsTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                language: {
                    emptyTable: '{{ __('message.No activations found.') }}'
                }
            });
        });
    </script>
@endpush
