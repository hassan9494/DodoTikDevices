@extends('layouts.admin')

@section('styles')

    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('content')

    <!-- Page Heading -->

    <h1 class="h3 mb-2 text-gray-800">{{__('message.Show Factory Parameters By Device')}}</h1>

    @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow mb-4">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Show factory parameters according to the device that finished recording parameters')}}</span>
            </h3>

        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table class="display table table-bordered" id="" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__('message.No.')}}</th>

                        <th>{{__('message.device_id')}}</th>

                        <th>{{__('message.Start Date')}}</th>

                        <th>{{__('message.End Date')}}</th>

                        <th>{{__('message.Option')}}</th>

                    </tr>

                    </thead>

                    <tbody>

                    @php

                        $no=0;

                    @endphp
                    @foreach ($factory->deviceFactories()->where('is_attached',0)->get() as $devFac)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{ $devFac->device->name }}</td>
                            <td>{{\Carbon\Carbon::parse($devFac->start_date)->setTimezone('Europe/Istanbul')->format('Y-d-m h:i a')}}</td>
                            <td>{{\Carbon\Carbon::parse($devFac->updated_at)->setTimezone('Europe/Istanbul')->format('Y-d-m h:i a')}}</td>
                            <td>
                                <a href="{{route('admin.factories.details', [$devFac->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-eye"></i> </a>

                            </td>
                        </tr>
                    @endforeach


                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card shadow mb-4">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Show factory parameters by device that is still recording parameters')}}</span>
            </h3>

        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table class="display table table-bordered" id="" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__('message.No.')}}</th>

                        <th>{{__('message.device_id')}}</th>

                        <th>{{__('message.Start Date')}}</th>

                        <th>{{__('message.Option')}}</th>

                    </tr>

                    </thead>

                    <tbody>

                    @php

                        $no=0;

                    @endphp
                    @foreach ($factory->deviceFactories()->where('is_attached',1)->get() as $devFac)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{ $devFac->device->name }}</td>
                            <td>{{\Carbon\Carbon::parse($devFac->start_date)->setTimezone('Europe/Istanbul')->format('Y-d-m h:i a')}}</td>
                            <td>
                                <a href="{{route('admin.factories.details', [$devFac->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-eye"></i> </a>
                            </td>
                        </tr>
                    @endforeach


                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('table.display').DataTable();
        } );
    </script>

    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>

@endpush
