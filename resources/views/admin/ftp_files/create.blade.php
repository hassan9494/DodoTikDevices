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

    <form action="{{ route('admin.components.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">

            <div class="picture-container">

                <div class="picture">

                    <img src="" class="picture-src" id="wizardPicturePreview" height="200px" width="400px" title=""/>

                    <input type="file" id="wizard-picture" name="image"
                           class=" {{$errors->first('image') ? "is-invalid" : "" }} ">
                    <div class="invalid-feedback" style="position: absolute;right: 0;bottom: -20px;">
                        {{ $errors->first('image') }}
                    </div>
                </div>


                <h6>{{__('message.Image')}}</h6>

            </div>

        </div>

        <div class="form-group ml-5">
            <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}}</label>
            <div class="col-sm-7">
                <input type="text" name='name' class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                       value="{{old('name')}}" id="name" placeholder="name">
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            </div>
        </div>

        <div class="form-group ml-5">
            <label for="desc" class="col-sm-2 col-form-label">Desc</label>
            <div class="col-sm-7">
                <textarea name="desc" id="desc" cols="30" rows="10"
                          class="form-control {{$errors->first('desc') ? "is-invalid" : "" }} "
                          id="summernote">{{old('desc')}}</textarea>
                <div class="invalid-feedback">
                    {{ $errors->first('desc') }}
                </div>
            </div>
        </div>

        <div class="form-group ml-5">
            <label for="settings" class="col-sm-2 col-form-label">{{__('message.settings')}}</label>
            <div class="col-sm-9">
                <select name='settings[]' class="form-control {{$errors->first('settings') ? "is-invalid" : "" }} select2" id="settings" multiple>
                    @foreach ($component_settings as $setting)
                        <option value="{{ $setting->id }}">{{ $setting->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('settings') }}
                </div>
            </div>
        </div>

{{--        <div class="form-group ml-5">--}}
{{--            <div class="row" style="text-align: -webkit-center;">--}}
{{--                <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">--}}
{{--                    <div class="card card-custom mb-4">--}}
{{--                        <div class="card-header border-0 pt-5">--}}
{{--                            <h3 class="card-title align-items-start flex-column">--}}
{{--                                <span class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.Settings')}} :  </span>--}}
{{--                            </h3>--}}
{{--                            <div class="card-toolbar">--}}
{{--                                <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card-body pt-2" style="position: relative;">--}}


{{--                            <div class="resize-triggers">--}}
{{--                                <div class="expand-trigger">--}}
{{--                                    <div style="width: 1291px; height: 399px;"></div>--}}
{{--                                </div>--}}
{{--                                <div class="contract-trigger"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="form-group ml-5">
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">{{__('message.Create')}}</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
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
