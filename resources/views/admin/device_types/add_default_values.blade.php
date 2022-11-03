@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.device_types.add_default',$type->id) }}" method="POST">
        @csrf

        <div class="form-groups">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Settings')}}</span>
                        </h3>


                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 ">
                            @foreach($type->deviceSettings as $set)
                                <div class="form-group ml-5">
                                    <label for="{{$set->name}}" class="col-sm-2 col-form-label">{{$set->name}} </label>
                                    <div class="col-sm-9">
                                        {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                        <input type="text" name="{{$set->name}}" placeholder="{{ $set->value}}"
                                               id="{{$set->name}}"
                                               class="form-control "
                                               value="{{ $set->pivot->value}}">
                                        {{--                    <div class="invalid-feedback">--}}
                                        {{--                        {{ $errors->first('name') }}--}}
                                        {{--                    </div>--}}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Parameters Order')}}</span>
                            </h3>


                        </div>


                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                @foreach($type->deviceParameters()->orderBy('order')->get() as $key=>$para)
                                    <div class="form-group ml-5">
                                        <label for="{{$para->code}}"
                                               class="col-sm-8 col-form-label">{{$para->name}} </label>
                                        <div class="col-sm-9">
                                            {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                            <input type="text" name="{{\Str::slug($para->code)}}_{{$key}}" placeholder="{{ $para->value}}"
                                                   id="{{$para->code}}"
                                                   class="form-control "
                                                   value="{{ $para->pivot->order}}">
                                            {{--                    <div class="invalid-feedback">--}}
                                            {{--                        {{ $errors->first('name') }}--}}
                                            {{--                    </div>--}}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Parameters Color')}}</span>
                            </h3>


                        </div>


                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                @foreach($type->deviceParameters()->orderBy('order')->get() as $key=>$para)
                                    <div class="form-group ml-5">
                                        <label for="{{$para->code}}"
                                               class="col-sm-8 col-form-label">{{$para->name}} </label>
                                        <div class="col-sm-9">
                                            {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                            <input type="color" name="{{\Str::slug($para->code)}}_{{$key}}_color"
                                                   placeholder="{{ $para->value}}" id="{{$para->code}}"
                                                   class="form-control "
                                                   value="{{ $para->pivot->color}}">
                                            {{--                    <div class="invalid-feedback">--}}
                                            {{--                        {{ $errors->first('name') }}--}}
                                            {{--                    </div>--}}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Parameters Length')}}</span>
                            </h3>


                        </div>


                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                @foreach($type->deviceParameters()->orderBy('order')->get() as $key=>$para)
                                    <div class="form-group ml-5">
                                        <label for="{{$para->code}}"
                                               class="col-sm-8 col-form-label">{{$para->name}} </label>
                                        <div class="col-sm-9">
                                            {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                            <input type="text" name="{{\Str::slug($para->code)}}_{{$key}}_length"
                                                   placeholder="{{ $para->value}}" id="{{$para->code}}"
                                                   class="form-control "
                                                   value="{{ $para->pivot->length}}" {{$type->is_gateway == 0 ? 'readonly' : ''}}>
                                            {{--                    <div class="invalid-feedback">--}}
                                            {{--                        {{ $errors->first('name') }}--}}
                                            {{--                    </div>--}}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Parameters Rate')}}</span>
                            </h3>


                        </div>


                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                @foreach($type->deviceParameters()->orderBy('order')->get() as $key=>$para)
                                    <div class="form-group ml-5">
                                        <label for="{{$para->code}}"
                                               class="col-sm-8 col-form-label">{{$para->name}} </label>
                                        <div class="col-sm-9">
                                            {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                            <input type="text" name="{{\Str::slug($para->code)}}_{{$key}}_rate"
                                                   placeholder="{{ $para->value}}" id="{{$para->code}}"
                                                   class="form-control "
                                                   value="{{ $para->pivot->rate}}" {{$type->is_gateway == 0 ? 'readonly' : ''}}>
                                            {{--                    <div class="invalid-feedback">--}}
                                            {{--                        {{ $errors->first('name') }}--}}
                                            {{--                    </div>--}}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
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
