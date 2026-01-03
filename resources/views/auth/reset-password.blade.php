<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dodotik Devices - Create New Password</title>

    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .screen__content {
            min-height: 520px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid #e53e3e;
            color: #822727;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-top: 0.75rem;
            font-size: 14px;
        }

        .auth-action-links {
            margin-top: 1.5rem;
            display: grid;
            gap: 0.75rem;
        }

        .auth-action-links .button {
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body style="padding: 30px 0;background: linear-gradient(90deg, #91d2f2, #61abd0);">
<div class="align" style="padding: 30px 0;">
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <img width="207" height="97" src="{{ asset('admin/img/logo.png') }}" class="attachment-medium size-medium" alt="Dodotik Devices Logo" loading="lazy">

                <form class="login" method="POST" action="{{ route('password.store') }}" style="padding: 30px;padding-top: 45px;width: 100%;">
                    <h3 style="color: #0b2e13;margin-bottom: 1.5rem;">Set a new password</h3>
                    <p style="color:#1b1e21;font-size: 14px;margin-bottom: 1.5rem;">Enter your email and choose a new password to complete the reset.</p>

                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="login__field">
                        <i class="login__icon fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="login__input" placeholder="Email address" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="login__field" style="margin-top: 1rem;">
                        <i class="login__icon fas fa-lock"></i>
                        <input id="password" type="password" name="password" class="login__input" placeholder="New password" required autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="login__field" style="margin-top: 1rem;">
                        <i class="login__icon fas fa-lock"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="login__input" placeholder="Confirm new password" required autocomplete="new-password">
                    </div>
                    @error('password_confirmation')
                        <div class="alert-danger">{{ $message }}</div>
                    @enderror

                    <button class="button login__submit" type="submit" style="margin-top: 1.5rem;">
                        <span class="button__text">{{ __('Reset Password') }}</span>
                    </button>
                </form>

                <div class="auth-action-links">
                    <a href="{{ route('login') }}" class="button login__submit" style="display: inline-block;">Back to login</a>
                    <a href="{{ route('register') }}" class="button login__submit" style="display: inline-block;">Create new account</a>
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

<script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
