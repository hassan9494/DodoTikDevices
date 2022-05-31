@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.devices.add_setting_values',$device->id) }}" method="POST">
        @csrf

        <div class="form-groups">
            @if($device->deviceSetting == null)
            @foreach($device->deviceType->deviceSettings as $set)
            <div class="form-group ml-5">
                <label for="{{$set->name}}" class="col-sm-2 col-form-label">{{$set->name}} </label>
                <div class="col-sm-9">

                    <input type="text" name="{{$set->name}}" placeholder="{{ $set->value}}" id="{{$set->name}}"
                           class="form-control "
                           value="{{ $set->pivot->value}}">
                </div>
            </div>
            @endforeach
            @else
                @foreach($device->deviceType->deviceSettings as $set)
                    <div class="form-group ml-5">
                        <label for="{{$set->name}}" class="col-sm-2 col-form-label">{{$set->name}} </label>
                        <div class="col-sm-9">
                            <input type="text" name="{{$set->name}}" placeholder="{{ $set->value}}" id="{{$set->name}}"
                                   class="form-control "
                                   value="{{json_decode($device->deviceSetting->settings,true)[$set->name] }}">
                            {{--                    <div class="invalid-feedback">--}}
                            {{--                        {{ $errors->first('name') }}--}}
                            {{--                    </div>--}}
                        </div>
                    </div>
                @endforeach
            @endif
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
