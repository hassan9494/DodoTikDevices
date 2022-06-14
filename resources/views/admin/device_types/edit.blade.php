@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.device_types.update',$type->id) }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name') ? old('name') : $type->name}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <label for="parameters" class="col-sm-4 col-form-label">{{__('message.parameters')}}</label>
            <div class="col-sm-9">
                <select name='parameters[]' class="form-control {{$errors->first('parameters') ? "is-invalid" : "" }} select2" id="parameters" multiple>
                    @foreach ($type->deviceParameters as $param)
                        <option selected value="{{ $param->id }}">{{ $param->name }}</option>
                    @endforeach
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
            <label for="settings" class="col-sm-4 col-form-label">{{__('message.settings')}}</label>
            <div class="col-sm-9">
                <select name='settings[]' class="form-control {{$errors->first('settings') ? "is-invalid" : "" }} select2" id="settings" multiple>
                    @foreach ($type->deviceSettings as $sett)
                        <option selected value="{{ $sett->id }}">{{ $sett->name }}</option>
                    @endforeach
                    @foreach ($devSettings as $setting)
                        <option value="{{ $setting->id }}">{{ $setting->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('settings') }}
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            <div class="col-sm-3">
                <button type="submit" class="btn btn-info">{{__('message.Update')}}</button>
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
