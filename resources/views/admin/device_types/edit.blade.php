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

            <div class="form-group col-md-4">
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
        <div class="form-group col-md-12">
            <div class="col-sm-3">
                <button type="submit" class="btn btn-info">{{__('message.Update')}}</button>
            </div>
        </div>
    </form>
@endsection
