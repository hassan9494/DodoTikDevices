@extends('layouts.admin')

@section('styles')

<link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('content')

<!-- Page Heading -->

<h1 class="h3 mb-2 text-gray-800">{{__('message.devices')}}</h1>

@if (session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<!-- DataTales Example -->

<div class="card shadow mb-4">
@can('isAdmin')
    <div class="card-header py-3">
        <a href="{{ route('admin.devices.create') }}" class="btn btn-pass">{{__('message.add_new')}}</a>
    </div>
@endcan
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{__('message.No.')}}</th>

                        <th>{{__('message.Name')}}</th>

                        <th>{{__('message.device_id')}}</th>

                        <th>{{__('message.type')}}</th>

                        <th>{{__('message.user email')}}</th>

                        <th>{{__('message.Option')}}</th>

                    </tr>

                </thead>

                <tbody>

                @php

                $no=0;
                $x=Session::get('current_time_zone');
                @endphp

                @foreach ($devices as $device)
                @if(auth()->user()->role=='Administrator')
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $device->name }}</td>
                        <td>{{ $device->device_id }}</td>
                        <td>
                            @if($device->deviceType != null)
                            {{ $device->deviceType->name }}
                            @endif
                        </td>

                        <td>
                            @if($device->user != null)
                            {{ $device->user->email }}
                            @endif
                        </td>
                        <td>
                            <a title="Edit" href="{{route('admin.devices.edit', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-edit"></i> </a>
                            @if($device->deviceType != null)
                            @if(count($device->deviceType->deviceSettings) > 0 )
                            <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-cogs"></i> </a>
                            @endif
                            @endif
                            <a href="{{route('admin.devices.add_device_limit_values', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-chart-line"></i> </a>
                            <a title="Edit Location" href="{{route('admin.devices.location', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-location-arrow"></i> </a>
                            <a title="Show" id="d_{{$device->id}}" href="{{route('admin.devices.show', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-eye"></i> </a>
                            <a title="Export" id="d_{{$device->id}}" href="{{route('admin.devices.export', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-file-export"></i> </a>
                            @if($device->deviceComponent != null)
{{--                            <a href="{{route('admin.device_components.edit', [$device->deviceComponent->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-images"></i> </a>--}}
                            @endif
                                <form method="POST" action="{{route('admin.devices.destroy', [$device->id])}}" class="d-inline" onsubmit="return confirm('{{__("message.Delete this type permanently?")}}')">

                                @csrf

                                <input type="hidden" name="_method" value="DELETE">

                                <button type="submit" value="Delete" class="btn btn-delete btn-sm">
                                <i class='fas fa-trash-alt'></i>
                                </button>

                            </form>

                        </td>

                    </tr>
                    @endif
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script>

        $(document).ready(function () {
            const date = new Date();
            const offset = date.getTimezoneOffset()
            console.log(offset);
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            @foreach($devices as $device)
               var rrr = document.getElementById('d_{{$device->id}}')
            text = rrr.href
            res = text.replace("offset",offset)
            rrr.href = res
            @endforeach
{{--            {{$no}} = offset--}}

        });



    </script>

<script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>

@endpush
