@extends('layouts.admin')

@section('styles')

<link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('content')

<!-- Page Heading -->

<h1 class="h3 mb-2 text-gray-800">{{__('message.Connected devices in the Factory')}}</h1>

@if (session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<div class="card shadow mb-4">
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            <form method="POST" action="{{route('admin.factories.detach', [$devFac->id])}}" class="d-inline" onsubmit="return confirm('{{__("message.Stop This Device?")}}')">

                                @csrf
                                <button type="submit" value="submit" class="btn btn-delete btn-sm">
                                    <i class='fas fa-stop-circle'></i>
                                </button>

                            </form>
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

<script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>

@endpush
