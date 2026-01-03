<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', 'Dodotik Devices') }} - {{ __('Verify Email') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body style="padding: 30px 0;background: linear-gradient(90deg, #91d2f2, #61abd0);">


<!-- *************************************************************************** -->
<div class="align" style="padding: 30px 0;">
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <img width="207" height="97" src="{{ asset('admin/img/logo.png') }}" class="attachment-medium size-medium" alt="{{ config('app.name') }}" loading="lazy" srcset="{{ asset('admin/img/logo.png') }}" sizes="(max-width: 207px) 100vw, 207px">

                <div class="card shadow-sm" style="border-radius: 1rem; background-color: rgba(255,255,255,0.92);">
                    <div class="card-header bg-primary text-white" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                        <h5 class="mb-0">{{ __('Verify Your Email Address') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3 text-muted">
                            {{ __('Thanks for joining Dodotik Devices! We just emailed you a confirmation link. Please click the verify button in that email to activate your account.') }}
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif

                        <p class="mb-4 text-muted">
                            {{ __('Didn\'t get the message? You can request a new link or sign out below.') }}
                        </p>

                        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                            @csrf
                            <button type="submit" class="button login__submit" style="width: 100%;">
                                <span class="button__text"><i class="fas fa-paper-plane mr-2"></i>{{ __('Resend Verification Email') }}</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="button login__submit" style="width: 100%; background: transparent; border: 1px solid #0b2e13; color: #0b2e13;">
                                <span class="button__text"><i class="fas fa-sign-out-alt mr-2"></i>{{ __('Log Out') }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 text-center text-white-75">
                    {{ __('Having trouble? Check your spam folder or contact support.') }}
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</div>
<!-- *************************************************************************** -->

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
