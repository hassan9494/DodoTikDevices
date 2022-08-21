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

    <form action="{{ route('admin.device_components.updateDisplay',$device->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="device_id" value="{{$device->id}}">
        <div class="row">
            @foreach($components as $key=>$component)
                @if(count($deviceComponents->where('component_id',$component->id)) > 0)

                <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark"
                                      style="font-size: 1rem;">{{$component->name}} </span>

                            </h3>

                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                    <li class="nav-item nav-item">
                                        <div class="col-md-2 d-flex flex-column justify-content-center">
                                            <label class="switch">
                                                <input type="hidden" name="component_{{$component->id}}" value="0">
                                                <input type="checkbox" name="component_{{$component->id}}" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <div class="card-body pt-2" style="position: relative;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            component
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img src="{{ asset('storage/'.$component->image) }}" width="430px"
                                                         height="300px">
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-center">
                                                        {{$component->desc}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            Setting
                                        </div>
                                        <div class="card-body">
                                            <div class="form-groups">
                                                <div class="form-group ml-6">
                                                    <label for="order_{{$component->id}}"
                                                           class="col-sm-6 col-form-label">{{__('message.Order')}} </label>
                                                    <div class="col-sm-6">
                                                        <input type="number" name="order_{{$component->id}}"
                                                               placeholder="order this component"
                                                               id="order_{{$component->id}}"
                                                               class="form-control {{$errors->first('order_'.$component->id) ? "is-invalid" : "" }} "
                                                               value="{{$deviceComponents->where('component_id',$component->id)->first()->order}}">
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('order_'.$component->id) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group ml-6">
                                                    <label for="width_{{$component->id}}"
                                                           class="col-sm-6 col-form-label">{{__('message.Width')}} </label>
                                                    <div class="col-sm-6">
                                                        <select name="width_{{$component->id}}" class="form-control {{$errors->first('width_'.$component->id) ? "is-invalid" : "" }} ">
                                                            <option disabled selected>choose one</option>
                                                            @for($i=1 ; $i <=12 ; $i++)
                                                                <option value="{{$i}}" {{$deviceComponents->where('component_id',$component->id)->first()->width == $i ? "selected" : ""}}>{{$i}}/12</option>
                                                            @endfor
                                                        </select>

                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('width_'.$component->id) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($device->deviceType != null)
                                                    @if($component->componentSettings != null)
                                                        @foreach($component->componentSettings as $comsetting)
                                                            <div class="form-group ml-6">
                                                                @include('admin.component.settings.'.json_decode($comsetting->settings)->type, [
                                                                       'name' => json_decode($comsetting->settings)->name."_".$component->id,
                                                                       'title' => json_decode($comsetting->settings)->title ,
                                                                       'id' => json_decode($comsetting->settings)->name . $key,
                                                                       'options' =>$comsetting->name == "parametrs" ? $device->deviceType->deviceParameters : $device->deviceType->deviceSettings,
                                                                       'choosen' =>$comsetting->name == "parametrs" ? json_decode($deviceComponents->where('component_id',$component->id)->first()->settings)->parameters : json_decode($deviceComponents->where('component_id',$component->id)->first()->settings)->settings,
                                                                       ])
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                @else
                    <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                        <div class="card card-custom mb-4">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark"
                                      style="font-size: 1rem;">{{$component->name}} </span>

                                </h3>

                                <div class="card-toolbar">
                                    <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                        <li class="nav-item nav-item">
                                            <div class="col-md-2 d-flex flex-column justify-content-center">
                                                <label class="switch">
                                                    <input type="hidden" name="component_{{$component->id}}" value="0">
                                                    <input type="checkbox" name="component_{{$component->id}}" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <div class="card-body pt-2" style="position: relative;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                component
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <img src="{{ asset('storage/'.$component->image) }}" width="430px"
                                                             height="300px">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="text-center">
                                                            {{$component->desc}}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                Setting
                                            </div>
                                            <div class="card-body">
                                                <div class="form-groups">
                                                    <div class="form-group ml-6">
                                                        <label for="order_{{$component->id}}"
                                                               class="col-sm-6 col-form-label">{{__('message.Order')}} </label>
                                                        <div class="col-sm-6">
                                                            <input type="number" name="order_{{$component->id}}"
                                                                   placeholder="order this component"
                                                                   id="order_{{$component->id}}"
                                                                   class="form-control {{$errors->first('order_'.$component->id) ? "is-invalid" : "" }} "
                                                                   value="{{old('order_'.$component->id)}}">
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('order_'.$component->id) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ml-6">
                                                        <label for="width_{{$component->id}}"
                                                               class="col-sm-6 col-form-label">{{__('message.Width')}} </label>
                                                        <div class="col-sm-6">
                                                            <select name="width_{{$component->id}}" class="form-control {{$errors->first('width_'.$component->id) ? "is-invalid" : "" }} ">
                                                                <option disabled selected>choose one</option>
                                                                @for($i=1 ; $i <=12 ; $i++)
                                                                    <option value="{{$i}}" >{{$i}}/12</option>
                                                                @endfor
                                                            </select>

                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('width_'.$component->id) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($device->deviceType != null)
                                                        @if($component->componentSettings != null)
                                                            @foreach($component->componentSettings as $comsetting)
                                                                <div class="form-group ml-6">
                                                                    @include('admin.component.settings.'.json_decode($comsetting->settings)->type, [
                                                                           'name' => json_decode($comsetting->settings)->name."_".$component->id,
                                                                           'title' => json_decode($comsetting->settings)->title ,
                                                                           'id' => json_decode($comsetting->settings)->name . $key,
                                                                           'options' =>$comsetting->name == "parametrs" ? $device->deviceType->deviceParameters : $device->deviceType->deviceSettings,
                                                                           'choosen' => null,
                                                                           ])
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                @endif
            @endforeach
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
