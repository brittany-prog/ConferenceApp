@extends('layouts.app')

@section('title', 'Days | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <span class="eyebrow">Schedule</span>
        <h2 style="margin: 10px 0 0;">Conference days</h2>
        <p class="muted">Use the day overview to understand the flow of the event, then open the full agenda for time-by-time planning.</p>

        @forelse ($days as $day)
            <article class="card" style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <h3 style="margin-top: 0;">{{ $day->name }}</h3>
                    <p class="muted"><strong>Date:</strong> {{ $day->event_date }}</p>
                    <p class="muted" style="margin-bottom: 0;">{{ $day->description }}</p>
                </div>
                <div>
                    <a href="/sessions" class="button secondary">View agenda</a>
                </div>
            </article>
        @empty
            <p class="muted">No day schedule has been published yet.</p>
        @endforelse
    </section>
@endsection
