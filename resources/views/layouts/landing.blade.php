@extends('layouts.app')

@section('title', $appSettings['brand_name'].' | Welcome')

@section('content')
    <section class="hero-grid" style="margin-bottom:18px;">
        <article class="panel stack">
            <span class="eyebrow">{{ $appSettings['homepage_subtitle'] }}</span>
            <h2 class="title">{{ $appSettings['brand_name'] }}</h2>
            <p class="muted" style="max-width:720px; font-size:1.08rem;">
                {{ $appSettings['brand_tagline'] }}
                {{ $appSettings['homepage_description'] }}
            </p>
            <div class="landing-hero-actions" style="margin-top: 8px;">
                <a href="/login" class="button landing-hero-action-button">Login to Continue</a>
                @if ($appSettings['registration_enabled'])
                    <a href="/register" class="button secondary landing-hero-action-button">Create Account</a>
                @endif
            </div>
            <div class="stat-row" style="margin-top: 16px;">
                <div class="stat">
                    <p class="muted">Days</p>
                    <h3 style="font-size:2rem; margin:0;">{{ $daysCount }}</h3>
                </div>
                <div class="stat">
                    <p class="muted">Sessions</p>
                    <h3 style="font-size:2rem; margin:0;">{{ $sessionsCount }}</h3>
                </div>
                <div class="stat">
                    <p class="muted">Speakers</p>
                    <h3 style="font-size:2rem; margin:0;">{{ $speakersCount }}</h3>
                </div>
            </div>
        </article>

        <aside class="panel stack">
            <span class="eyebrow">Why Login</span>
            <h3 style="margin:0;">Your full conference experience is inside</h3>
            <div class="card">
                <strong>Personal schedule</strong>
                <p class="muted" style="margin-bottom:0;">Save sessions and build your own event plan.</p>
            </div>
            <div class="card">
                <strong>Speaker and session details</strong>
                <p class="muted" style="margin-bottom:0;">Browse the agenda, tracks, and people in one place.</p>
            </div>
            <div class="card">
                <strong>Attendee-ready app</strong>
                <p class="muted" style="margin-bottom:0;">A private conference hub for your event community.</p>
            </div>
        </aside>
    </section>

    <section class="panel stack" style="margin-bottom:18px;">
        <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
            <div>
                <span class="eyebrow">Preview</span>
                <h3 style="margin: 12px 0 0;">Latest conference updates</h3>
            </div>
            <a href="/login">Login to view full agenda</a>
        </div>

        @forelse ($announcements as $announcement)
            <article class="card">
                <h4 style="margin-top:0;">{{ $announcement->title }}</h4>
                <p class="muted" style="margin-bottom:0;">{{ $announcement->message }}</p>
            </article>
        @empty
            <p class="muted">Announcements will appear here as the event gets closer.</p>
        @endforelse
    </section>

    @if ($sponsors->isNotEmpty())
        <section class="panel stack">
            <div>
                <span class="eyebrow">Partners</span>
                <h3 style="margin: 12px 0 0;">Sponsors and supporting organizations</h3>
            </div>
            <div class="grid grid-3">
                @foreach ($sponsors as $sponsor)
                    <article class="card">
                        @if ($sponsor->logo_path)
                            <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" style="width:100%; max-height:120px; object-fit:contain; margin-bottom:16px;">
                        @endif
                        <h4 style="margin-top:0;">{{ $sponsor->name }}</h4>
                        @if ($sponsor->tier)
                            <p class="muted">{{ $sponsor->tier }}</p>
                        @endif
                        @if ($sponsor->description)
                            <p class="muted" style="margin-bottom:0;">{{ $sponsor->description }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @push('page-styles')
        <style>
            .landing-hero-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .landing-hero-action-button {
                min-height: 48px;
                justify-content: center;
            }

            @media (max-width: 720px) {
                .landing-hero-actions {
                    flex-direction: column;
                }

                .landing-hero-action-button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
