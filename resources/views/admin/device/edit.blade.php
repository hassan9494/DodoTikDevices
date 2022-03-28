@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.devices.update',$device->id) }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name') ? old('name') : $device->name}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="device_id" class="col-sm-2 col-form-label">{{__('message.device_id')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="device_id" placeholder="device_id" id="device_id"
                           class="form-control {{$errors->first('device_id') ? "is-invalid" : "" }} "
                           value="{{old('device_id') ? old('device_id') : $device->device_id}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('device_id') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <label for="type" class="col-sm-4 col-form-label">{{__('message.type')}}</label>
            <div class="col-sm-9">
                <select name='type' class="form-control {{$errors->first('type') ? "is-invalid" : "" }}" id="parameters" >
                    <option selected disabled>chose one</option>
                    @foreach ($types as $type)
                        <option {{$device->type_id == $type->id ? "selected" : ""}} value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('type') }}
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
