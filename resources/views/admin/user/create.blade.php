@extends('layouts.admin')

@section('styles')
    <style>
        .picture-container {
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .picture {
            width: 300px;
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

        input[type="radio"] {
            cursor: pointer;
        }

        input[type="radio"]:focus {
            color: #495057;
            background-color: #0477b1;
            border-color: transparent;
            outline: 0;
            box-shadow: none;
        }
    </style>
@endsection
@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group col-md-6 ">
                <label for="email" class="col-sm-2 col-form-label">{{__('message.Email')}}</label>
                <div class="col-sm-9">
                    <input type="email" name='email'
                           class="form-control {{$errors->first('email') ? "is-invalid" : "" }} "
                           value="{{old('email')}}" id="email" placeholder="Email">
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="username" class="col-sm-2 col-form-label">{{__('message.Username')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="username" placeholder="Username" id="username"
                           class="form-control {{$errors->first('username') ? "is-invalid" : "" }} "
                           value="{{old('username')}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name')}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="phone" class="col-sm-2 col-form-label">{{ __('message.Phone') }}</label>
                <div class="col-sm-9">
                    <input type="text" name="phone" placeholder="{{ __('message.Phone') }}" id="phone"
                           class="form-control {{$errors->first('phone') ? "is-invalid" : "" }} "
                           value="{{ old('phone') }}">
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                </div>
            </div>


            @can('isAdmin')
                <div class="form-group col-md-6 ">
                    <label for="entidad" class="col-sm-2 col-form-label">{{__('message.Role')}}</label>
                    <div class="col-sm-9">
                        <select name='role' class="form-control {{$errors->first('role') ? "is-invalid" : "" }} "
                                id="role" style="appearance: auto;">
                            <option disabled selected>{{__('message.Choose_One')}}</option>
                            <option value="Administrator">Administrator</option>
                            <option value="user">User</option>
                        </select>
                        <div class="invalid-feedback">
                            {{ $errors->first('role') }}
                        </div>
                    </div>
                </div>
            @endcan

            @can('isUser')

                <div class="form-group col-md-6">
                    <div class="col-sm-9">
                        <input type="hidden" name='role'
                               class="form-control {{$errors->first('role') ? "is-invalid" : "" }} " value="user"
                               id="role" placeholder="role">
                        <div class="invalid-feedback">
                            {{ $errors->first('role') }}
                        </div>
                    </div>
                </div>
            @endcan

            <div class="form-group col-md-6 ">
                <label for="password" class="col-sm-2 col-form-label">{{__('message.Password')}}</label>
                <div class="col-sm-9">
                    <input type="password" name='password'
                           class="form-control {{$errors->first('password') ? "is-invalid" : "" }} "
                           value="{{old('password')}}" id="password" placeholder="Password">
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>
            </div>


            <div class="form-group col-md-12">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info">{{__('message.Create')}}</button>
                </div>
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
@endpush
