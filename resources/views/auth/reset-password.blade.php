@extends('layouts.app')

@section('title', 'Set Password | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 520px; margin: 0 auto;">
        <span class="eyebrow">Secure Access</span>
        <h2 style="margin: 16px 0 8px;">Set your password</h2>
        <p class="lede" style="margin:0;">Choose a password you’ll use to sign in to {{ $appSettings['brand_name'] }}.</p>

        <form method="POST" action="/reset-password" class="stack" style="margin-top: 24px;">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-section">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required>

                <label for="password">New password</label>
                <input id="password" type="password" name="password" required>

                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="action-row">
                <p class="subtle" style="margin:0;">Use at least 8 characters.</p>
                <button type="submit" class="button">Save Password</button>
            </div>
        </form>
    </section>
@endsection
