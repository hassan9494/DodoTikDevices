@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.factories.attach',$factory->id) }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Factory')}} </label>
                <div class="col-sm-6">
                    {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name') ? old('name') : $factory->name}}" readonly>
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="device" class="col-sm-2 col-form-label">{{__('message.device')}}</label>
                <div class="col-sm-6">
                    <select name='device' class="form-control {{$errors->first('device') ? "is-invalid" : "" }}" id="device" >
                        <option selected disabled>chose one</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('device') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info">{{__('message.Start')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')

@endpush
