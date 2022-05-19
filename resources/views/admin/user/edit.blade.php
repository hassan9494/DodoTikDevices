@extends('layouts.admin')

@section('content')

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('admin.users.update',$user->id) }}" method="POST">
    @csrf

    <div class="form-groups">

        <div class="form-group col-md-4 ">
            <label for="email" class="col-sm-2 col-form-label">{{__('message.Email')}}</label>
            <div class="col-sm-9">
                <input type="email" name='email' class="form-control {{$errors->first('email') ? "is-invalid" : "" }} " value="{{old('email') ? old('email') : $user->email}}" id="email" placeholder="Email">
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="username" class="col-sm-2 col-form-label">{{__('message.Username')}} </label>
            <div class="col-sm-9">
                {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                <input type="text" name="username" placeholder="username del formador" id="username" cols="40" rows="10"  class="form-control {{$errors->first('username') ? "is-invalid" : "" }} " value="{{old('username') ? old('username') : $user->username}}" >
                <div class="invalid-feedback">
                    {{ $errors->first('username') }}
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
            <div class="col-sm-9">
                {{-- <input type="text" class="form-control" id="title" placeholder="Title"> --}}

                <input type="text" name="name" placeholder="name" id="name"  class="form-control {{$errors->first('name') ? "is-invalid" : "" }} " value="{{old('name') ? old('name') : $user->name}}" >
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            </div>
        </div>






        @can('isAdmin')
            <div class="form-group col-md-4 ">
                <label for="entidad" class="col-sm-2 col-form-label">{{__('message.Role')}}</label>
                <div class="col-sm-9">
                    <select name='role' class="form-control {{$errors->first('role') ? "is-invalid" : "" }} " id="role" style="appearance: auto;">
                        <option disabled selected>{{__('message.Choose_One')}}</option>
                        <option value="Administrator" {{$user->role == "Administrator" ? "selected" : ""}}>{{__('message.Administrator')}}</option>
                        <option value="user" {{$user->role == "user" ? "selected" : ""}}>{{__('message.User')}}</option>
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('role') }}
                    </div>
                </div>
            </div>
        @endcan





    </div>
    <div class="form-group col-md-12">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-info">{{__('message.Update')}}</button>
        </div>
    </div>
  </form>
@endsection
