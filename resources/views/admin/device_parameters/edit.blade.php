@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.device_parameters.update',$parameter->id) }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group col-md-6">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name') ? old('name') : $parameter->name}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="code" class="col-sm-2 col-form-label">{{__('message.Code')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="code" placeholder="code" id="code"
                           class="form-control {{$errors->first('code') ? "is-invalid" : "" }} "
                           value="{{old('code') ? old('code') : $parameter->code}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
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
