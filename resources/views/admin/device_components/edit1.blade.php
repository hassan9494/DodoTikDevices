@extends('layouts.admin')

@section('styles')
    <style>
        .picture-container {
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .picture {
            width: 800px;
            height: 400px;
            background-color: #999999;
            border: 4px solid #CCCCCC;
            color: #FFFFFF;
            /* border-radius: 50%; */
            margin: 5px auto;
            overflow: hidden;
            transition: all 0.2s;
            -webkit-transition: all 0.2s;
        }

        .picture:hover {
            border-color: #2ca8ff;
        }

        .picture input[type="file"] {
            cursor: pointer;
            display: block;
            height: 100%;
            left: 0;
            opacity: 0 !important;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .picture-src {
            width: 100%;
            height: 100%;
        }
    </style>

@endsection

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.device_components.update',$deviceComponent->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group ml-5">
            <label for="device_id" class="col-sm-2 col-form-label">{{__('message.Device')}}</label>
            <div class="col-sm-9">
                <select name='device_id' class="form-control {{$errors->first('device_id') ? "is-invalid" : "" }}"
                        id="device_id" readonly disabled>
                    <option selected disabled>chose one</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id }}" {{$deviceComponent->device_id == $device->id ? "selected" : ""}}>{{ $device->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('device_id') }}
                </div>
            </div>
            <input type="hidden" name="device_id" value="{{$deviceComponent->device_id}}">
        </div>
        <div class="form-group ml-5">
            <label for="component_id" class="col-sm-2 col-form-label">{{__('message.Component')}}</label>
            <div class="col-sm-9">
                <select name='component_id' class="form-control {{$errors->first('component_id') ? "is-invalid" : "" }}"
                        id="component_id" readonly disabled>
                    <option selected disabled>chose one</option>
                    @foreach ($components as $component)
                        <option value="{{ $component->id }}" {{$deviceComponent->component_id == $component->id ? "selected" : ""}}>{{ $component->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('component_id') }}
                </div>
            </div>
            <input type="hidden" name="component_id" value="{{$deviceComponent->component_id}}">
        </div>
        <div class="form-group ml-5">
            <label for="order" class="col-sm-6 col-form-label">{{__('message.Order')}} </label>
            <div class="col-sm-9">
                <input type="number" name="order" placeholder="order this component" id="order"
                       class="form-control {{$errors->first('order') ? "is-invalid" : "" }} "
                       value="{{old('order') ? old('order') : $deviceComponent->order}}">
                <div class="invalid-feedback">
                    {{ $errors->first('order') }}
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <label for="width" class="col-sm-6 col-form-label">{{__('message.Width')}} </label>
            <div class="col-sm-9">
                <select name="width" class="form-control {{$errors->first('width') ? "is-invalid" : "" }} ">
                    <option disabled>choose one</option>
                    @for($i=1 ; $i <=12 ; $i++)
                    <option value="{{$i}}" {{$i == $deviceComponent->width ? "selected" : ""}}>{{$i}}/12</option>
                    @endfor
                </select>
{{--                <input type="number" name="width" placeholder="width this component" id="width"--}}
{{--                       class="form-control {{$errors->first('width') ? "is-invalid" : "" }} "--}}
{{--                       value="{{old('width') ? old('width') : $deviceComponent->width}}">--}}
                <div class="invalid-feedback">
                    {{ $errors->first('width') }}
                </div>
            </div>
        </div>
        @if($deviceComponent->component->componentSettings != null)
            @foreach($deviceComponent->component->componentSettings as $comsetting)
        <div class="form-group ml-5">
            @include('admin.component.settings.'.json_decode($comsetting->settings)->type, [
                   'name' => json_decode($comsetting->settings)->name,
                   'title' => json_decode($comsetting->settings)->title,
                   'options' => $deviceComponent->device->deviceType->deviceParameters,
                   'choosen' => json_decode($deviceComponent->settings)->parameters
                   ])
        </div>
            @endforeach
        @endif
{{--        <div class="form-group ml-5">--}}
{{--            <label for="setting" class="col-sm-6 col-form-label">{{__('message.Settings')}} </label>--}}
{{--            <div class="col-sm-9">--}}
{{--                <textarea  name="setting" placeholder="setting this component" id="setting"--}}
{{--                       class="form-control {{$errors->first('setting') ? "is-invalid" : "" }} "--}}
{{--                          >{{old('setting') ? old('setting') : $deviceComponent->settings}}</textarea>--}}
{{--                <div class="invalid-feedback">--}}
{{--                    {{ $errors->first('setting') }}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row">
{{--            @foreach($components as $component)--}}

{{--                <div class="col-lg-6 col-xxl-12 order-1 order-xxl-1 mb-4">--}}
{{--                    <div class="card card-custom mb-4">--}}
{{--                        <div class="card-header border-0 pt-5">--}}
{{--                            <h3 class="card-title align-items-start flex-column">--}}
{{--                                <span class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{$component->name}} </span>--}}

{{--                            </h3>--}}

{{--                            <div class="card-toolbar">--}}
{{--                                <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">--}}
{{--                                    <li class="nav-item nav-item">--}}
{{--                                        <div class="col-md-2 d-flex flex-column justify-content-center">--}}
{{--                                            <label class="switch">--}}
{{--                                                <input type="hidden" name="component_{{$component->id}}" value="0">--}}
{{--                                                <input type="checkbox" name="component_{{$component->id}}" {{in_array($component->id,json_decode($deviceComponent->components)) ? "checked" : ""}}>--}}
{{--                                                <span class="slider round" ></span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                        </div>--}}

{{--                        <div class="card-body pt-2" style="position: relative;">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <img src="{{ asset('storage/'.$component->image) }}" width="430px" height="300px">--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <p class="text-center">--}}
{{--                                        {{$component->desc}}--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}


{{--            @endforeach--}}
{{--            <input type="hidden" name="device_component" value="{{$deviceComponent->id}}">--}}
        </div>
        <div class="form-group ml-5">
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">{{__('message.Update')}}</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
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

@endpush
