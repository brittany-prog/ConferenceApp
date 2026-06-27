@extends('layouts.app')

@section('title', 'Forgot Password | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 520px; margin: 0 auto;">
        <span class="eyebrow">Password Help</span>
        <h2 style="margin: 16px 0 8px;">Reset your password</h2>
        <p class="lede" style="margin:0;">Enter your email and we’ll send you a secure link to set a new password.</p>

        <form method="POST" action="/forgot-password" class="stack" style="margin-top: 24px;">
            @csrf
            <div class="form-section">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="action-row">
                <p class="subtle" style="margin:0;">Use the same email address you use to sign in.</p>
                <button type="submit" class="button">Email Reset Link</button>
            </div>
        </form>

        <p class="muted" style="margin-top: 18px;">Remembered it? <a href="/login">Back to login</a></p>
    </section>
@endsection
