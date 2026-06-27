@extends('layouts.app')

@section('title', 'Verify Login Code | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 520px; margin: 0 auto;">
        <span class="eyebrow">Step 2</span>
        <h2 style="margin: 16px 0 8px;">Check your email</h2>
        <p class="lede" style="margin:0;">We sent a 6-digit login code to <strong>{{ $email }}</strong>. Enter it below to finish signing in.</p>

        <form method="POST" action="/login/verify" class="stack" style="margin-top: 24px;">
            @csrf
            <div class="form-section">
                <label for="code">Login code</label>
                <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" value="{{ old('code') }}" required>
                <p class="field-help">Codes expire after a short time for security.</p>
            </div>
            <div class="action-row">
                <p class="subtle" style="margin:0;">Need a new code? <a href="/login">Sign in again</a>.</p>
                <button type="submit" class="button">Verify and Login</button>
            </div>
        </form>
    </section>
@endsection
