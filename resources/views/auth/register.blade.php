@extends('layouts.app')

@section('title', 'Register | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 560px; margin: 0 auto;">
        <span class="eyebrow">New Account</span>
        <h2 style="margin: 16px 0 8px;">Create an attendee login</h2>
        <p class="lede" style="margin:0;">Registration is limited to approved attendees. Use the event access code from your invitation email to create your account.</p>

        <form method="POST" action="/register" class="stack" style="margin-top: 24px;">
            @csrf
            <div class="form-section">
                <div>
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label for="event_access_code">Event access code</label>
                    <input id="event_access_code" type="text" name="event_access_code" value="{{ old('event_access_code') }}" required>
                </div>
                <div class="grid grid-2">
                    <div>
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div>
                        <label for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>
                </div>
            </div>
            <div class="action-row">
                <p class="subtle" style="margin:0;">After registration, we’ll email you a login code to complete sign-in.</p>
                <button type="submit" class="button">Register</button>
            </div>
        </form>
    </section>
@endsection
