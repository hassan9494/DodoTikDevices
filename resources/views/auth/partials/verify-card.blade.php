<style>
    .verify-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1.5rem;
        background: radial-gradient(circle at 0% 0%, #14b8b5 0%, #0d6b8a 40%, #0b1f36 100%);
    }

    .verify-card {
        width: 100%;
        max-width: 460px;
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(245, 248, 250, 0.96));
        box-shadow: 0 24px 60px rgba(5, 31, 52, 0.35);
        padding: 2.25rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .verify-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(0, 152, 157, 0.15), transparent 55%);
        pointer-events: none;
    }

    .verify-card__logo {
        margin-bottom: 1.5rem;
    }

    .verify-card__title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0b2e4f;
        margin-bottom: 0.75rem;
    }

    .verify-card__subtitle {
        color: #26435f;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .verify-card__alert {
        background: rgba(20, 184, 181, 0.1);
        border: 1px solid rgba(20, 184, 181, 0.35);
        color: #056163;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .verify-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.75rem;
        z-index: 1;
    }

    .verify-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        padding: 0.875rem 1.25rem;
        font-size: 0.95rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
    }

    .verify-button i {
        margin-right: 0.5rem;
    }

    .verify-button--primary {
        background: linear-gradient(135deg, #00b3b0, #00989d);
        color: #ffffff;
        box-shadow: 0 16px 32px rgba(0, 152, 157, 0.35);
    }

    .verify-button--primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 36px rgba(0, 152, 157, 0.4);
    }

    .verify-button--secondary {
        background: transparent;
        color: #0b2e4f;
        border: 1px solid rgba(5, 31, 52, 0.18);
    }

    .verify-button--secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(11, 46, 79, 0.15);
    }

    .verify-button--link {
        background: transparent;
        color: #d64045;
    }

    .verify-button--link:hover {
        color: #a22f33;
    }

    .verify-card__footer {
        color: rgba(11, 46, 79, 0.72);
        font-size: 0.85rem;
        margin: 0;
        z-index: 1;
    }

    @media (max-width: 480px) {
        .verify-card {
            padding: 1.75rem 1.5rem;
        }

        .verify-card__title {
            font-size: 1.35rem;
        }
    }
</style>

<div class="verify-page">
    <div class="verify-card">
        <div class="verify-card__logo">
            <img src="{{ asset('admin/img/logo.png') }}" alt="{{ config('app.name') }}" height="72">
        </div>

        <h1 class="verify-card__title">{{ __('Verify Your Email Address') }}</h1>
        <p class="verify-card__subtitle">
            {{ __('We just emailed you a confirmation link. Please tap the verify button in that message to activate your account.') }}
        </p>

        @if (session('status') === 'verification-link-sent' || session('resent'))
            <div class="verify-card__alert">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="verify-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="verify-button verify-button--primary">
                    <i class="fas fa-paper-plane"></i> {{ __('Resend Verification Email') }}
                </button>
            </form>

            @if (!empty($showDashboardLink))
                <a href="{{ route('admin.dashboard') }}" class="verify-button verify-button--secondary">
                    <i class="fas fa-tachometer-alt"></i> {{ __('Go to Dashboard') }}
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="verify-button verify-button--link">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}
                </button>
            </form>
        </div>

        <p class="verify-card__footer">
            {{ __('Didn\'t receive the message yet? Check your spam folder or request a fresh link above.') }}
        </p>
    </div>
</div>
