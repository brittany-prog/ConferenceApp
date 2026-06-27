@extends('layouts.app')

@section('title', 'Login | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell auth-shell">
        <section class="panel auth-login-panel">
            <div class="auth-login-hero">
                <span class="eyebrow">Welcome Back</span>
                <h2 class="auth-login-title">{{ $appSettings['login_heading'] ?: 'Sign in to '.$appSettings['brand_name'] }}</h2>
                <p class="auth-login-copy">{{ $appSettings['login_description'] }}</p>
            </div>

            <form method="POST" action="/login" class="auth-login-form">
                @csrf

                <div class="auth-login-fields">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">

                    <label class="check-row auth-login-check">
                        <input type="checkbox" name="remember">
                        <span>Remember me on this device</span>
                    </label>
                </div>

                <div class="auth-login-actions">
                    <p class="subtle auth-login-note">{{ $appSettings['login_admin_note'] }}</p>
                    <button type="submit" class="button auth-login-submit">Login</button>
                </div>
            </form>

            <div class="auth-login-links">
                <p class="muted">Forgot your password? <a href="/forgot-password">Reset it by email</a></p>

                @if ($appSettings['registration_enabled'])
                    <p class="muted">Have your event access code already? <a href="/register">Create your app account</a></p>
                @endif
            </div>
        </section>
    </div>

    @push('page-styles')
        <style>
            .auth-shell {
                max-width: 680px;
                margin: 0 auto;
            }

            .auth-login-panel,
            .auth-login-form,
            .auth-login-fields,
            .auth-login-actions,
            .auth-login-links {
                display: grid;
            }

            .auth-login-panel {
                gap: 18px;
                padding: 22px;
            }

            .auth-login-hero {
                display: grid;
                gap: 10px;
            }

            .auth-login-title {
                margin: 0;
                line-height: 0.98;
                letter-spacing: -0.04em;
                font-size: clamp(2rem, 4vw, 2.8rem);
            }

            .auth-login-copy,
            .auth-login-note {
                margin: 0;
            }

            .auth-login-copy {
                color: var(--brand-muted);
                line-height: 1.6;
                max-width: 48ch;
            }

            .auth-login-form {
                gap: 16px;
            }

            .auth-login-fields {
                gap: 12px;
                padding: 16px;
                border-radius: 20px;
                border: 1px solid var(--brand-line);
                background: var(--form-section-bg);
            }

            .auth-login-check {
                margin-top: 2px;
            }

            .auth-login-actions {
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 14px;
                align-items: center;
            }

            .auth-login-submit {
                min-width: 128px;
                justify-content: center;
            }

            .auth-login-links {
                gap: 10px;
            }

            .auth-login-links p {
                margin: 0;
            }

            @media (max-width: 720px) {
                .auth-shell {
                    max-width: none;
                }

                .auth-login-panel {
                    gap: 16px;
                    padding: 16px;
                }

                .auth-login-title {
                    font-size: clamp(1.7rem, 8vw, 2.2rem);
                }

                .auth-login-fields {
                    gap: 10px;
                    padding: 14px;
                    border-radius: 16px;
                }

                .auth-login-actions {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }

                .auth-login-submit {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
