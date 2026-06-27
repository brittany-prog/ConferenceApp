@extends('layouts.app')

@section('title', $speaker->name.' | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 980px; margin: 0 auto;">
        <a href="/speakers">Back to speakers</a>

        <div class="grid grid-2" style="align-items:start;">
            <div class="card card-loud" style="text-align:center;">
                @if ($speaker->profile_photo_path)
                    <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" class="speaker-detail-avatar">
                @else
                    <div class="speaker-detail-avatar speaker-detail-avatar--fallback">
                        {{ strtoupper(substr($speaker->name, 0, 1)) }}
                    </div>
                @endif
                <span class="eyebrow">Speaker</span>
                <h2 style="margin: 14px 0 8px;">{{ $speaker->name }}</h2>
                <div class="meta-stack" style="justify-content:center; margin-top:10px;">
                    @if ($speaker->title || $speaker->organization)
                        <span class="meta-pill">{{ $speaker->title }} @if($speaker->title && $speaker->organization) at {{ $speaker->organization }} @endif</span>
                    @endif
                    @if ($speaker->location)
                        <span class="meta-pill">{{ $speaker->location }}</span>
                    @endif
                </div>

                @if (auth()->check() && auth()->id() !== $speaker->id)
                    <div class="speaker-action-row" style="justify-content:center; margin-top:16px;">
                        <a href="/messages/{{ $speaker->id }}" class="button speaker-action-button">Message Speaker</a>
                    </div>
                @elseif (auth()->check() && auth()->id() === $speaker->id && $sessions->isNotEmpty())
                    <div class="speaker-action-row" style="justify-content:center; margin-top:16px;">
                        <a href="/share/sessions/{{ $sessions->first()->id }}/speaker" class="button speaker-action-button">Download a Graphic to Promote Your Session</a>
                    </div>
                @endif
            </div>

            <div class="stack">
                <div class="card card-loud">
                    <p class="muted" style="margin:0 0 10px;">About</p>
                    <p style="margin:0;">{{ $speaker->bio ?: 'Speaker bio coming soon.' }}</p>
                </div>

                @if ($speaker->linkedin_url || $speaker->website_url)
                    <div class="card card-loud">
                        <p class="muted" style="margin:0 0 12px;">Links</p>
                        <div class="speaker-action-row">
                            @if ($speaker->linkedin_url)
                                <a href="{{ $speaker->linkedin_url }}" target="_blank" rel="noreferrer" class="button secondary speaker-action-button">LinkedIn</a>
                            @endif
                            @if ($speaker->website_url)
                                <a href="{{ $speaker->website_url }}" target="_blank" rel="noreferrer" class="button secondary speaker-action-button">Website</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="stack">
            <div>
                <span class="eyebrow">Sessions</span>
                <h3 style="margin: 10px 0 0;">Where to find {{ $speaker->name }}</h3>
            </div>

            @forelse ($sessions as $session)
                <article class="card card-loud">
                    <div class="meta-stack" style="margin-bottom:8px;">
                        <span class="meta-pill">{{ $session->day->name ?? 'Conference Day' }}</span>
                        <span class="meta-pill">{{ $session->start_time ?? 'TBD' }} - {{ $session->end_time ?? 'TBD' }}</span>
                    </div>
                    <h4 style="margin:0 0 8px;"><a href="/sessions/{{ $session->id }}">{{ $session->title }}</a></h4>
                    <p class="muted" style="margin:0;">{{ $session->track->name ?? 'General Session' }} | {{ $session->location ?? 'TBD' }}</p>
                    @php($coSpeakers = $session->resolvedSpeakers()->reject(fn ($linkedSpeaker) => $linkedSpeaker->id === $speaker->id))
                    @if ($coSpeakers->isNotEmpty())
                        <p class="muted" style="margin:10px 0 0;">
                            <strong>Also presenting with:</strong>
                            @foreach ($coSpeakers as $coSpeaker)
                                <a href="/speakers/{{ $coSpeaker->id }}">{{ $coSpeaker->name }}</a>@if (! $loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </article>
            @empty
                <div class="card">
                    <p class="muted" style="margin:0;">This speaker is not linked to any sessions yet.</p>
                </div>
            @endforelse
        </div>
    </section>

    @push('page-styles')
        <style>
            .speaker-detail-avatar {
                width: 148px;
                height: 148px;
                object-fit: cover;
                border-radius: 36px;
                margin: 0 auto 16px;
                display: block;
                box-shadow: 0 12px 24px rgba(17, 23, 48, 0.14);
            }

            .speaker-detail-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 3rem;
                font-weight: 800;
            }

            .speaker-action-row {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .speaker-action-button {
                min-height: 48px;
                justify-content: center;
            }

            @media (max-width: 720px) {
                .speaker-action-row {
                    flex-direction: column;
                }

                .speaker-action-button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
