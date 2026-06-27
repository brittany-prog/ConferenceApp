@extends('layouts.app')

@section('title', 'Venue | '.$appSettings['brand_name'])

@section('content')
    @php($venueImage = !empty($venue['image_path']) ? asset('storage/'.$venue['image_path']) : asset('jsu-student-center.jpg'))

    <section class="panel page-hero page-hero-image venue-hero" style="margin-bottom:18px; background-image:url('{{ $venueImage }}'); background-size:cover; background-position:center;">
        @if (auth()->check() && ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true))
            <a href="/admin/venue" class="admin-edit-chip" aria-label="Edit venue page">
                <span aria-hidden="true">✎</span>
                <span>Edit</span>
            </a>
        @endif
        <div class="page-hero-inner">
            <span class="page-kicker">Venue Info</span>
            <h2 class="page-title">{{ $venue['name'] }}</h2>
            <p class="page-subtitle">{{ $venue['subtitle'] }}</p>
        </div>
    </section>

    <section class="grid grid-2" style="margin-bottom:18px;">
        <section class="panel stack">
            <div>
                <span class="eyebrow">Arrival</span>
                <h3 style="margin:12px 0 0;">Getting to the conference</h3>
            </div>

            <div class="card card-loud">
                <p class="muted" style="margin:0 0 8px;">Venue</p>
                <strong>{{ $venue['name'] }}</strong>
            </div>

            <div class="card">
                <p class="muted" style="margin:0 0 8px;">Arrival note</p>
                <p style="margin:0;">{{ $venue['arrival_note'] }}</p>
            </div>
        </section>

        <section class="panel stack">
            <div>
                <span class="eyebrow">Parking</span>
                <h3 style="margin:12px 0 0;">Where to park</h3>
            </div>

            <div class="card card-loud">
                <p style="margin:0;">{{ $venue['parking_note'] }}</p>
            </div>

            <div class="card">
                <p class="muted" style="margin:0 0 8px;">Helpful tip</p>
                <p style="margin:0;">{{ $venue['helpful_tip'] }}</p>
            </div>
        </section>
    </section>

    <section class="panel stack">
        <div>
            <span class="eyebrow">Before You Go</span>
            <h3 style="margin:12px 0 0;">Quick planning notes</h3>
        </div>

        <div class="grid grid-3 venue-notes">
            <article class="card card-loud">
                <p class="muted" style="margin:0 0 8px;">Arrival timing</p>
                <p style="margin:0;">{{ $venue['arrival_timing_note'] }}</p>
            </article>
            <article class="card card-loud">
                <p class="muted" style="margin:0 0 8px;">Best use</p>
                <p style="margin:0;">{{ $venue['best_use_note'] }}</p>
            </article>
            <article class="card card-loud">
                <p class="muted" style="margin:0 0 8px;">Need the agenda?</p>
                <p style="margin:0;"><a href="/sessions">{{ $venue['schedule_note'] }}</a></p>
            </article>
        </div>
    </section>

    @push('page-styles')
        <style>
            .venue-hero::before {
                background: linear-gradient(135deg, rgba(13, 15, 34, 0.76), rgba(23, 26, 55, 0.38));
            }

            .venue-hero .page-hero-inner,
            .venue-hero .page-title,
            .venue-hero .page-subtitle {
                color: #ffffff;
            }

            .venue-hero .page-subtitle {
                color: rgba(255, 255, 255, 0.88);
            }

            .venue-hero .page-kicker {
                background: rgba(255, 255, 255, 0.14);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            @media (max-width: 720px) {
                .venue-notes {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>
    @endpush
@endsection
