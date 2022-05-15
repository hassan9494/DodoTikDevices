@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.devices.add_limit_values',$device->id) }}" method="POST">
        @csrf
        <div class="container-fluid">

            <div class="row" style="text-align: -webkit-center;">
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">Min Values</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                <div class="form-groups">
                                    @if($device->limitValues == null)
                                        @foreach($device->deviceType->deviceParameters as $para)
                                            <div class="form-group ml-12">
                                                <label for="{{$para->code}}"
                                                       class="col-sm-8 col-form-label">{{$para->name}} </label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="{{$para->code}}_min"
                                                           placeholder="Insert {{ $para->name}} Min Value"
                                                           id="{{$para->code}}"
                                                           class="form-control  {{$errors->first($para->code."_min") ? "is-invalid" : "" }} ">
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first($para->code."_min") }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($device->deviceType->deviceParameters as $para)
                                            <div class="form-group ml-5">
                                                <label for="{{$para->code}}"
                                                       class="col-sm-2 col-form-label">{{$para->name}} </label>
                                                <div class="col-sm-9">
                                                    {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                                    <input type="text" name="{{$para->code}}_min"
                                                           placeholder="{{ $para->value}}" id="{{$para->code}}"
                                                           class="form-control   {{$errors->first($para->code."_min") ? "is-invalid" : "" }} "
                                                           value="{{json_decode($device->limitValues->min_value,true)[$para->code] }}">
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first($para->code."_min") }}
                                                                        </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xxl-6 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">Max Values</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2" style="position: relative;">
                            <div class="col-md-12 ">
                                <div class="form-groups">
                                    @if($device->limitValues == null)
                                        @foreach($device->deviceType->deviceParameters as $para)
                                            <div class="form-group ml-12">
                                                <label for="{{$para->code}}"
                                                       class="col-sm-8 col-form-label">{{$para->name}} </label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="{{$para->code}}_max"
                                                           placeholder="Insert {{ $para->name}} Max Value"
                                                           id="{{$para->code}}"
                                                           class="form-control  {{$errors->first($para->code."_max") ? "is-invalid" : "" }} ">
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first($para->code."_max") }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($device->deviceType->deviceParameters as $para)
                                            <div class="form-group ml-5">
                                                <label for="{{$para->code}}"
                                                       class="col-sm-2 col-form-label">{{$para->name}} </label>
                                                <div class="col-sm-9">
                                                    {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                                                    <input type="text" name="{{$para->code}}_max"
                                                           placeholder="{{ $para->value}}" id="{{$para->code}}"
                                                           class="form-control {{$errors->first($para->code."_max") ? "is-invalid" : "" }} "
                                                           value="{{json_decode($device->limitValues->max_value,true)[$para->code] }}">
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first($para->code."_max") }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
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


@endpush
