@extends('layouts.app')

@section('content')
    <main class="login-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Reset Password</div>
                        <div class="card-body">

                            @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif

                            <form action="{{ route('forget.password.post') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Send Password Reset Link
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection




{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}

{{--<head>--}}

{{--    <meta charset="utf-8">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
{{--    <meta name="description" content="">--}}
{{--    <meta name="author" content="">--}}

{{--    <title>Dodotik Devices - Admin Login</title>--}}

{{--    <!-- Custom fonts for this template-->--}}
{{--    <link href="{{asset('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">--}}

{{--    <!-- Custom styles for this template-->--}}
{{--    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">--}}
{{--    <style>--}}
{{--        #login::-webkit-input-placeholder{--}}
{{--            font-size: 11px;--}}
{{--        }--}}
{{--    </style>--}}

{{--</head>--}}

{{--<body style="padding: 30px 0;background: linear-gradient(90deg, #91d2f2, #61abd0);">--}}


{{--<!-- *************************************************************************** -->--}}
{{--<div class="align" style="padding: 30px 0;">--}}
{{--    <div class="container">--}}
{{--        <div class="screen">--}}
{{--            <div class="screen__content">--}}
{{--                <img width="207" height="97" src="{{ asset('admin/img/logo.png')}}" class="attachment-medium size-medium" alt="" loading="lazy" srcset="{{ asset('admin/img/logo.png')}}" sizes="(max-width: 207px) 100vw, 207px">--}}

{{--                --}}{{--                    <div class="card-header">Reset Password</div>--}}
{{--                --}}{{--                    <div class="card-body">--}}

{{--                @if (Session::has('message'))--}}
{{--                    <div class="alert alert-success" role="alert">--}}
{{--                        {{ Session::get('message') }}--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <form class="login" action="{{ route('forget.password.post') }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <div class="login__field">--}}
{{--                        <label for="email_address" class="col-md-12 col-form-label text-md-right">E-Mail Address</label>--}}
{{--                        <div class="col-md-12">--}}
{{--                            <input type="text" id="email_address" class="form-control" name="email" required autofocus>--}}
{{--                            @if ($errors->has('email'))--}}
{{--                                <span class="text-danger">{{ $errors->first('email') }}</span>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    --}}{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                    --}}{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                    --}}{{--                                    Send Password Reset Link--}}
{{--                    --}}{{--                                </button>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    <button class="button login__submit">--}}

{{--                        <span class="button__text">Send Password Reset Link</span>--}}
{{--                    </button>--}}
{{--                </form>--}}

{{--                --}}{{--                    </div>--}}
{{--                --}}{{--                <div class="">--}}
{{--                --}}{{--                <h5 style="color: #0b2e13"><a  href="{{ route('register') }}" class="button login__submit">create new account</a></h5>--}}
{{--                --}}{{--                <h5 style="color: #0b2e13"><a  href="{{ route('forget.password.get') }}" class="button login__submit">Reset Password</a></h5>--}}
{{--                --}}{{--                  <div class="social-icons">--}}
{{--                --}}{{--                    <a href="#" class="social-login__icon fab fa-instagram"></a>--}}
{{--                --}}{{--                    <a href="#" class="social-login__icon fab fa-facebook"></a>--}}
{{--                --}}{{--                    <a href="#" class="social-login__icon fab fa-twitter"></a>--}}
{{--                --}}{{--                  </div>--}}
{{--                --}}{{--                </div>--}}


{{--            </div>--}}
{{--            --}}{{--            <div class="screen__background">--}}
{{--            --}}{{--                <span class="screen__background__shape screen__background__shape4"></span>--}}
{{--            --}}{{--                <span class="screen__background__shape screen__background__shape3"></span>--}}
{{--            --}}{{--                <span class="screen__background__shape screen__background__shape2"></span>--}}
{{--            --}}{{--                <span class="screen__background__shape screen__background__shape1"></span>--}}
{{--            --}}{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<!-- *************************************************************************** -->--}}
{{--<!-- Bootstrap core JavaScript-->--}}
{{--<script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>--}}
{{--<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>--}}

{{--<!-- Core plugin JavaScript-->--}}
{{--<script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>--}}

{{--<!-- Custom scripts for all pages-->--}}
{{--<script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>--}}

{{--</body>--}}

{{--</html>--}}

