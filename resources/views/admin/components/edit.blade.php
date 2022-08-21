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

    <form action="{{ route('admin.components.update',$component->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <div class="picture-container">

                <div class="picture">

                    <img src="{{ asset('storage/'.$component->image) }}" class="picture-src" id="wizardPicturePreview" height="200px" width="400px" title=""/>

                    <input type="file" class=" {{$errors->first('image') ? "is-invalid" : "" }} " id="wizard-picture" name="image">
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
                       value="{{old('name') ? old('name') : $component->name}}" id="name" placeholder="name">
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            </div>
        </div>

            <div class="form-group ml-5">
                <label for="desc" class="col-sm-2 col-form-label">Desc</label>
                <div class="col-sm-7">
                  <textarea name="desc" id="desc" cols="30" rows="10" class="form-control {{$errors->first('desc') ? "is-invalid" : "" }} " id="summernote">{{old('desc') ? old('desc') : $component->desc}}</textarea>
                  <div class="invalid-feedback">
                    {{ $errors->first('desc') }}
                </div>
                </div>
            </div>

        <div class="form-group ml-5">
            <label for="settings" class="col-sm-4 col-form-label">{{__('message.settings')}}</label>
            <div class="col-sm-9">
                <select name='settings[]' class="form-control {{$errors->first('settings') ? "is-invalid" : "" }} select2" id="settings" multiple>
                    @foreach ($component->componentSettings as $param)
                        <option selected value="{{ $param->id }}">{{ $param->name }}</option>
                    @endforeach
                    @foreach ($component_settings as $component_setting)
                        <option value="{{ $component_setting->id }}">{{ $component_setting->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('settings') }}
                </div>
            </div>
        </div>

        {{--    <div class="form-group ml-5">--}}
        {{--        <label for="link" class="col-sm-2 col-form-label">Link</label>--}}
        {{--        <div class="col-sm-7">--}}
        {{--          <input type="text" name='link' class="form-control {{$errors->first('link') ? "is-invalid" : "" }} " value="{{old('link')}}" id="link" placeholder="Link">--}}
        {{--          <div class="invalid-feedback">--}}
        {{--            {{ $errors->first('link') }}--}}
        {{--        </div>--}}
        {{--        </div>--}}
        {{--      </div>--}}

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
            $('#settings').select2({
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
