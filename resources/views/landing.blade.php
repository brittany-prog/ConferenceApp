@extends('layouts.app')

@section('title', $appSettings['brand_name'].' | Welcome')

@section('content')
    @php($lightSurfaceBrandMark = $appSettings['favicon_asset'])
    <section class="panel stack" style="max-width: 560px; margin: 0 auto; text-align: center; padding: 42px 24px;">
        @if (auth()->check() && ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true))
            <a href="/admin/settings" class="admin-edit-chip" aria-label="Edit landing page">
                <span aria-hidden="true">✎</span>
                <span>Edit</span>
            </a>
        @endif
        <div style="display:flex; justify-content:center;">
            <img src="{{ $lightSurfaceBrandMark }}" alt="{{ $appSettings['brand_name'] }} logo" style="width:88px; height:88px; object-fit:contain; box-shadow:0 18px 40px rgba(17,24,39,0.12);">
        </div>

        <div>
            <span class="eyebrow">{{ $appSettings['homepage_subtitle'] }}</span>
            <h2 style="font-size:clamp(2.4rem, 8vw, 4.5rem); line-height:0.92; margin:22px 0 0;">{{ $appSettings['brand_name'] }}</h2>
            <p class="muted" style="font-size:1.05rem; max-width:420px; margin:24px auto 0;">
                {{ $appSettings['homepage_description'] }}
            </p>
        </div>

        <div class="stack" style="max-width: 320px; margin: 12px auto 0; width:100%;">
            <a href="/login" class="button" style="width:100%; padding:14px 18px;">Login</a>
            <a href="/register" class="button secondary" style="width:100%; padding:14px 18px;">Register for the App</a>
            @if ($appSettings['has_public_ticket_link'])
                <a href="{{ $appSettings['public_ticket_url'] }}" target="_blank" rel="noreferrer" class="button secondary" style="width:100%; padding:14px 18px;">{{ $appSettings['public_ticket_label'] ?: 'Get Tickets' }}</a>
            @endif
        </div>

        <p class="muted" style="margin: 2px 0 0;">
            Want to look around first? <a href="/agenda-preview">{{ $appSettings['agenda_preview_label'] ?: 'Preview the agenda' }}</a>
        </p>

        <div class="grid" style="grid-template-columns:repeat(3, minmax(0, 1fr)); gap:10px; margin-top: 6px;">
            <div class="card" style="padding:14px 10px; border-radius:18px;">
                <strong style="display:block; font-size:1.2rem;">{{ $daysCount }}</strong>
                <span class="muted" style="font-size:0.88rem;">Days</span>
            </div>
            <div class="card" style="padding:14px 10px; border-radius:18px;">
                <strong style="display:block; font-size:1.2rem;">{{ $sessionsCount }}</strong>
                <span class="muted" style="font-size:0.88rem;">Sessions</span>
            </div>
            <div class="card" style="padding:14px 10px; border-radius:18px;">
                <strong style="display:block; font-size:1.2rem;">{{ $speakersCount }}</strong>
                <span class="muted" style="font-size:0.88rem;">Speakers</span>
            </div>
        </div>

        @if ($sponsors->isNotEmpty())
            <div style="padding-top: 8px; display:grid; gap:14px;">
                <div>
                    <p class="muted" style="font-size:0.85rem; margin-bottom:6px;">Sponsors & Exhibitors</p>
                    <p class="muted" style="margin:0;">Supported by the organizations helping bring {{ $appSettings['brand_name'] }} to life.</p>
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(132px, 1fr)); gap:10px; width:100%;">
                    @foreach ($sponsors as $sponsor)
                        @php($logoWidth = max(54, 94 - (((int) $sponsor->display_order - 1) * 4)))
                        @php($logoHeight = max(28, 46 - (((int) $sponsor->display_order - 1) * 2)))
                        <a href="/sponsors/{{ $sponsor->id }}" class="card" style="padding:12px; border-radius:18px; text-decoration:none; color:inherit; min-height:92px; display:grid; place-items:center; gap:8px;">
                            @if ($sponsor->logo_path)
                                <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" style="width:{{ $logoWidth }}px; height:{{ $logoHeight }}px; object-fit:contain;">
                            @else
                                <span class="muted" style="font-size:0.85rem;">{{ $sponsor->name }}</span>
                            @endif
                            @if ($sponsor->tier)
                                <span class="muted" style="font-size:0.76rem;">{{ $sponsor->tier }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
