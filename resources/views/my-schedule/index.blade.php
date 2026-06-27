@extends('layouts.app')

@section('title', 'My Schedule | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <span class="eyebrow">My Schedule</span>
        <h2 style="margin: 10px 0 0;">Saved sessions</h2>
        <p class="muted">Keep an eye on overlaps so your personal agenda stays realistic.</p>

        @if ($conflicts->isNotEmpty())
            <section class="card stack" style="border-color: rgba(245, 158, 11, 0.4); background: rgba(255, 251, 235, 0.9);">
                <div>
                    <h3 style="margin:0 0 8px;">Overlap warning</h3>
                    <p class="muted" style="margin:0;">Some saved sessions compete for the same time slot.</p>
                </div>

                @foreach ($conflicts as $conflict)
                    <div class="card" style="padding:16px;">
                        <p class="muted" style="margin:0 0 8px;">{{ $conflict['day']->name ?? 'Conference Day' }}</p>
                        <strong>{{ $conflict['first']->title }}</strong>
                        <span class="muted"> conflicts with </span>
                        <strong>{{ $conflict['second']->title }}</strong>
                    </div>
                @endforeach
            </section>
        @endif

        @forelse ($scheduleDays as $scheduleDay)
            <section class="card stack">
                <div>
                    <h3 style="margin:0;">{{ $scheduleDay['day']->name ?? 'Conference Day' }}</h3>
                    <p class="muted" style="margin:8px 0 0;">{{ $scheduleDay['day']->event_date ?? 'Date TBD' }}</p>
                </div>

                @foreach ($scheduleDay['sessions'] as $session)
                    @php($sessionSpeakers = $session->resolvedSpeakers())
                    <article class="card">
                        <h4 style="margin-top:0;"><a href="/sessions/{{ $session->id }}">{{ $session->title }}</a></h4>
                        <p class="muted"><strong>Time:</strong> {{ $session->start_time ?? 'TBD' }} - {{ $session->end_time ?? 'TBD' }}</p>
                        <p class="muted"><strong>Track:</strong> {{ $session->track->name ?? 'TBD' }} | <strong>Location:</strong> {{ $session->location ?? 'TBD' }}</p>
                        @if ($sessionSpeakers->isNotEmpty())
                            <div style="display:flex; gap:12px; align-items:flex-start; margin-bottom:14px;">
                                <div style="display:flex; margin-top:2px;">
                                    @foreach ($sessionSpeakers->take(3) as $index => $speaker)
                                        @if ($speaker->profile_photo_path)
                                            <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" style="width:52px; height:52px; object-fit:cover; border-radius:16px; border:2px solid #fff; margin-left:{{ $index === 0 ? '0' : '-10px' }}; box-shadow:0 8px 18px rgba(17, 23, 48, 0.12);">
                                        @else
                                            <div style="width:52px; height:52px; border-radius:16px; background:linear-gradient(135deg, var(--brand-primary), var(--brand-accent)); display:grid; place-items:center; color:#fff; font-size:1rem; font-weight:800; border:2px solid #fff; margin-left:{{ $index === 0 ? '0' : '-10px' }}; box-shadow:0 8px 18px rgba(17, 23, 48, 0.12);">
                                                {{ strtoupper(substr($speaker->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div>
                                    <p class="muted" style="margin:0;">{{ $sessionSpeakers->count() > 1 ? 'Speakers' : 'Speaker' }}</p>
                                    <p style="margin:0;">
                                        @foreach ($sessionSpeakers as $speaker)
                                            <a href="/speakers/{{ $speaker->id }}">{{ $speaker->name }}</a>@if (! $loop->last), @endif
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="/sessions/{{ $session->id }}/toggle-save">
                            @csrf
                            <button type="submit" class="button secondary">Remove from schedule</button>
                        </form>
                    </article>
                @endforeach
            </section>
        @empty
            <p class="muted">You have not saved any sessions yet.</p>
            <a href="/sessions" class="button">Browse sessions</a>
        @endforelse
    </section>
@endsection
