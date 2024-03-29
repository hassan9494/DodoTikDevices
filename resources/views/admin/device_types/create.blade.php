@extends('layouts.admin')

@section('styles')
    <style>
        .picture-container {
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .picture {
            width: 300px;
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

        input[type="radio"] {
            cursor: pointer;
        }

        input[type="radio"]:focus {
            color: #495057;
            background-color: #0477b1;
            border-color: transparent;
            outline: 0;
            box-shadow: none;
        }
    </style>
@endsection
@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.device_types.store') }}" method="POST">
        @csrf
        <div class="form-groups">
            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name')}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="parameters" class="col-sm-2 col-form-label">{{__('message.parameters')}}</label>
                <div class="col-sm-9">
                    <select name='parameters[]'
                            class="form-control {{$errors->first('parameters') ? "is-invalid" : "" }} select2"
                            id="parameters" multiple>
                        @foreach ($parameters as $parameter)
                            <option value="{{ $parameter->id }}">{{ $parameter->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('parameters') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="settings" class="col-sm-2 col-form-label">{{__('message.settings')}}</label>
                <div class="col-sm-9">
                    <select name='settings[]'
                            class="form-control {{$errors->first('settings') ? "is-invalid" : "" }} select2"
                            id="settings" multiple>
                        @foreach ($devSettings as $setting)
                            <option value="{{ $setting->id }}">{{ $setting->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('settings') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="settings" class="col-sm-2 col-form-label">{{__('message.Is Gateway')}}</label>

                <div class="col-md-2 d-flex flex-column justify-content-center">
                    <label class="switch">
                        <input type="hidden" name="is_gateway" value="0">
                        <input type="checkbox" name="is_gateway" id="is_gateway">
                        <span class="slider round"></span>
                    </label>

                </div>
            </div>
            <div class="form-group ml-5" style="display: none" id="encode_type_div">
                <label for="encode_type" class="col-sm-2 col-form-label">{{__('message.Encode Type')}}</label>
                <div class="col-sm-9">
                    <select name='encode_type'
                            class="form-control {{$errors->first('encode_type') ? "is-invalid" : "" }}"
                            id="encode_type">

                        <option value="1">{{__('message.Encode To Decimal')}}</option>
                        <option value="2">{{__('message.Encode To Float')}}</option>

                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('encode_type') }}
                    </div>
                </div>
            </div>

            <div class="form-group ml-5">
                <label for="settings" class="col-sm-2 col-form-label">{{__('message.Is Need Response')}}</label>

                <div class="col-md-2 d-flex flex-column justify-content-center">
                    <label class="switch">
                        <input type="hidden" name="is_need_response" value="0">
                        <input type="checkbox" name="is_need_response">
                        <span class="slider round"></span>
                    </label>

                </div>
            </div>
            <div class="form-group col-md-12">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info">{{__('message.Create')}}</button>
                </div>
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
    </script>
    <script>
        $(document).ready(function () {
            $('#settings').select2({
                placeholder: "Choose Some settings"
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#is_gateway').change(function () {
                if (this.checked)
                    //  ^
                    $('#encode_type_div').fadeIn('slow');
                else
                    $('#encode_type_div').fadeOut('slow');
            });
        });
    </script>
@endpush
