@php($settings = \App\Support\AppSettings::all())
<p>Hello {{ $user->name }},</p>

<p>We received a request to reset your {{ $settings['brand_name'] }} password. Use the secure link below to choose a new one.</p>

<p><a href="{{ $resetUrl }}">Reset your password</a></p>

<p>If you did not request this, you can safely ignore this email.</p>
