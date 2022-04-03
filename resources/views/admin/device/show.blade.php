@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
                <h1 class="text-center">{{__('message.Name')}} : {{$device->name}} </h1>
                <h1 class="text-center">{{__('message.device_id')}} : {{$device->device_id}} </h1>
                <h1 class="text-center">{{__('message.type')}} : {{$device->deviceType->name}} </h1>
            </div>
            <div class="col-md-12">
                <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h1>{{__('message.settings')}} : </h1>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                @foreach($device->deviceType->deviceSettings as $setting)
                                    <th>{{$setting->name}}</th>
                                @endforeach
                                <th>{{__('message.Option')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                    @if($device->deviceSetting == null)
                                    @foreach($device->deviceType->deviceSettings as $setting)
                                        <td>{{$setting->pivot->value}}</td>
                                    @endforeach
                                    @else
                                    @foreach($device->deviceType->deviceSettings as $setting)
                                            <td>{{json_decode($device->deviceSetting->settings,true)[$setting->name] }}</td>
                                        @endforeach
                                    @endif
                                <td>
                                    <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-cogs"></i> </a>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h1>{{__('message.parameters')}} : </h1>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    @foreach($device->deviceType->deviceParameters as $parameter)
                                        <th>{{$parameter->name}}</th>
                                    @endforeach
                                    <th>{{__('message.Time')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($device->deviceParameters as $para)
                                <tr>

                                    @if($device->deviceParameters == null)
                                        @foreach($device->deviceParameters as $parameter)
                                            <td>{{$parameter}}</td>
                                        @endforeach
                                    @else
                                        @foreach($device->deviceType->deviceParameters as $parameter)
                                            <td>{{json_decode($para->parameters,true)[$parameter->name] }}</td>

                                        @endforeach
                                    @endif
                                    <td>
                                       {{$para->time_of_read}}

                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <script>
        // Prepare the preview for profile picture
        $("#wizard-picture").change(function () {
            readURL(this);
        });

        //Function to show image before upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#parameters').select2({
                placeholder: "Choose Some parameters"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#settings').select2({
                placeholder: "Choose Some settings"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
@endpush
