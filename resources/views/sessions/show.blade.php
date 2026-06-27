@extends('layouts.app')

@section('title', $session->title.' | '.$appSettings['brand_name'])

@section('content')
    @php
        $isPreviewMode = $isPreviewMode ?? false;
        $backPath = $isPreviewMode ? '/agenda-preview' : '/sessions';
    @endphp
    <section class="panel stack" style="max-width: 980px; margin: 0 auto;">
        <a href="{{ $backPath }}">Back to sessions</a>

        <div class="card card-loud stack">
            <div>
                <span class="eyebrow">{{ $session->track->name ?? 'Session' }}</span>
                <h2 style="margin: 12px 0 0;">{{ $session->title }}</h2>
            </div>
            <div class="meta-stack">
                <span class="meta-pill">{{ $session->day->name ?? 'TBD' }}</span>
                <span class="meta-pill">{{ $session->start_time ?? 'TBD' }} - {{ $session->end_time ?? 'TBD' }}</span>
                <span class="meta-pill">{{ $session->location ?? 'TBD' }}</span>
            </div>
            @if ($isPreviewMode)
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    @if (!empty($registrationUrl))
                        <a href="{{ $registrationUrl }}" target="_blank" rel="noreferrer" class="button">{{ $appSettings['public_ticket_label'] ?: 'Get Tickets' }}</a>
                    @endif
                    <a href="/login" class="button secondary">Login for the full app</a>
                </div>
            @elseif ($session->resolvedSpeakers()->contains(fn ($speaker) => $speaker->id === auth()->id()))
                <div>
                    <a href="/share/sessions/{{ $session->id }}/speaker" class="button">Download a Graphic to Promote This Session</a>
                </div>
            @endif
        </div>

        @php($sessionSpeakers = $session->resolvedSpeakers())

        @if ($sessionSpeakers->isNotEmpty())
            <div class="stack">
                <div>
                    <span class="eyebrow">{{ $sessionSpeakers->count() > 1 ? 'Speakers' : 'Speaker' }}</span>
                    <h3 style="margin:10px 0 0;">Meet the presenter{{ $sessionSpeakers->count() > 1 ? 's' : '' }}</h3>
                </div>

                <div class="grid grid-2" style="align-items:stretch;">
                    @foreach ($sessionSpeakers as $speaker)
                        <div class="card card-loud" style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                            @if ($speaker->profile_photo_path)
                                <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" class="session-speaker-avatar">
                            @else
                                <div class="session-speaker-avatar session-speaker-avatar--fallback">
                                    {{ strtoupper(substr($speaker->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="muted" style="margin:0;">Speaker</p>
                                <h3 style="margin:6px 0;"><a href="/speakers/{{ $speaker->id }}">{{ $speaker->name }}</a></h3>
                                <p class="muted" style="margin:0;">{{ $speaker->title }} @if($speaker->organization) at {{ $speaker->organization }} @endif</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="card">
            <p class="muted" style="margin:0 0 10px;">Overview</p>
            <p style="margin:0;">{{ $session->description }}</p>
        </div>

        @unless($isPreviewMode)
            <section class="grid grid-2" style="align-items:start;">
                <div class="card card-loud">
                    <p class="muted" style="margin:0 0 8px;">Session feedback</p>
                    @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                        @if (($feedbackSummary['count'] ?? 0) > 0)
                            <h3 style="margin:0 0 8px;">{{ number_format((float) $feedbackSummary['average'], 1) }} / 5</h3>
                            <p class="muted" style="margin:0;">Based on {{ $feedbackSummary['count'] }} response{{ $feedbackSummary['count'] === 1 ? '' : 's' }}.</p>
                        @else
                            <h3 style="margin:0 0 8px;">No feedback yet</h3>
                            <p class="muted" style="margin:0;">No attendee responses have been submitted for this session yet.</p>
                        @endif

                        @if ($session->feedback->isNotEmpty())
                            <div class="stack" style="margin-top:16px;">
                                @foreach ($session->feedback->take(3) as $feedback)
                                    <div style="padding-top:12px; border-top:1px solid var(--brand-line);">
                                        <p class="muted" style="margin:0 0 6px;">{{ $feedback->user->name ?? 'Attendee' }} · {{ $feedback->rating }}/5</p>
                                        <p style="margin:0;">{{ $feedback->comment ?: 'Shared a rating without a written comment.' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <h3 style="margin:0 0 8px;">Share your perspective</h3>
                        <p class="muted" style="margin:0;">Your feedback goes to the organizers to help improve future {{ $appSettings['brand_name'] }} programming.</p>
                    @endif
                </div>

                <form method="POST" action="/sessions/{{ $session->id }}/feedback" class="card stack">
                    @csrf
                    <div>
                        <p class="muted" style="margin:0 0 8px;">Your feedback</p>
                        <h3 style="margin:0;">{{ $userFeedback ? 'Update your session feedback' : 'Rate this session' }}</h3>
                    </div>

                    <div>
                        <label for="rating">Rating</label>
                        <select id="rating" name="rating" required>
                            <option value="">Choose a rating</option>
                            @for ($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected((int) old('rating', $userFeedback->rating ?? 0) === $rating)>{{ $rating }} / 5</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="comment">Comment</label>
                        <textarea id="comment" name="comment" rows="5" placeholder="What worked well? What should we know for next time?">{{ old('comment', $userFeedback->comment ?? '') }}</textarea>
                    </div>

                    <div class="action-row">
                        <p class="subtle" style="margin:0;">Your feedback helps the organizers improve future {{ $appSettings['brand_name'] }} sessions.</p>
                        <button type="submit" class="button">Save feedback</button>
                    </div>
                </form>
            </section>
        @endunless
    </section>
@endsection

@push('page-styles')
    <style>
        .session-speaker-avatar {
            width: 88px;
            height: 88px;
            object-fit: cover;
            border-radius: 24px;
            box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
        }

        .session-speaker-avatar--fallback {
            display: grid;
            place-items: center;
            background: var(--button-gradient);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
            box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
        }
    </style>
@endpush
