@extends('layouts.app')

@section('title', $attendee->name.' | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 920px; margin: 0 auto;">
        <div class="grid grid-2" style="align-items:start;">
            <div class="card card-loud" style="text-align:center;">
                @if ($attendee->profile_photo_path)
                    <img src="{{ asset('storage/'.$attendee->profile_photo_path) }}" alt="{{ $attendee->name }} profile photo" class="attendee-detail-avatar">
                @else
                    <div class="attendee-detail-avatar attendee-detail-avatar--fallback">
                        {{ strtoupper(substr($attendee->name, 0, 1)) }}
                    </div>
                @endif
                @if ($attendee->is_speaker)
                    <span class="eyebrow">Speaker</span>
                @endif
                @if ($attendee->is_exhibitor)
                    <span class="eyebrow">Exhibitor</span>
                @endif
                @if ($attendee->is_admin)
                    <span class="eyebrow">Admin</span>
                @endif
                @if (! $attendee->is_speaker && ! $attendee->is_admin && ! $attendee->is_exhibitor)
                    <span class="eyebrow">Attendee</span>
                @endif
                <h2 style="margin: 14px 0 8px;">{{ $attendee->name }}</h2>
                <div class="meta-stack" style="justify-content:center; margin-top:10px;">
                    @if ($attendee->title || $attendee->organization)
                        <span class="meta-pill">{{ $attendee->title }} @if($attendee->title && $attendee->organization) at @endif{{ $attendee->organization }}</span>
                    @endif
                    @if ($attendee->location)
                        <span class="meta-pill">{{ $attendee->location }}</span>
                    @endif
                    @if ($attendee->is_exhibitor && $attendee->sponsor)
                        <span class="meta-pill">Representing {{ $attendee->sponsor->name }}</span>
                    @endif
                </div>
            </div>

            <div class="stack">
                <div>
                    <a href="/attendees">Back to attendees</a>
                    <h3 style="margin: 10px 0 0;">About</h3>
                </div>

                <div class="card card-loud">
                    <p style="margin:0;">{{ $attendee->bio ?: 'This attendee has not added a bio yet.' }}</p>
                </div>

                @if ($attendee->interests)
                    <div class="card card-loud">
                        <p class="muted" style="margin:0 0 8px;">Interests</p>
                        <p style="margin:0;">{{ $attendee->interests }}</p>
                    </div>
                @endif

                @if ($attendee->linkedin_url || $attendee->website_url)
                    <div class="card">
                        <p class="muted" style="margin:0 0 12px;">Links</p>
                        <div class="detail-link-actions">
                            @if ($attendee->linkedin_url)
                                <a href="{{ $attendee->linkedin_url }}" target="_blank" rel="noreferrer" class="button secondary detail-action-button">LinkedIn</a>
                            @endif
                            @if ($attendee->website_url)
                                <a href="{{ $attendee->website_url }}" target="_blank" rel="noreferrer" class="button secondary detail-action-button">Website</a>
                            @endif
                        </div>
                    </div>
                @endif

                @if (auth()->id() !== $attendee->id)
                    <div class="card card-loud attendee-connect-card">
                        <p class="muted" style="margin:0 0 12px;">Want to connect around a session, idea, or follow-up?</p>
                        <div class="detail-link-actions">
                            <a href="/messages/{{ $attendee->id }}" class="button detail-action-button">Send Message</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('page-styles')
        <style>
            .attendee-detail-avatar {
                width: 160px;
                height: 160px;
                object-fit: cover;
                border-radius: 40px;
                margin: 0 auto 16px;
                display: block;
                box-shadow: 0 10px 22px rgba(17, 23, 48, 0.14);
            }

            .attendee-detail-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 3rem;
                font-weight: 800;
                box-shadow: 0 10px 22px rgba(17, 23, 48, 0.14);
            }

            .attendee-connect-card {
                background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(246,250,255,0.94));
            }

            .detail-link-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .detail-action-button {
                min-height: 48px;
                justify-content: center;
            }

            @media (max-width: 720px) {
                .detail-link-actions {
                    flex-direction: column;
                }

                .detail-action-button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
