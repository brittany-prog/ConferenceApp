@extends('layouts.app')

@section('title', 'Speaker Graphics | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 980px; margin: 0 auto;">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Share Card</span>
                <h2 style="margin: 10px 0 0;">Your speaker graphics</h2>
                <p class="muted" style="max-width: 680px;">Choose the session you want to promote and we’ll open the branded social graphic for it.</p>
            </div>
            <a href="/app" class="button secondary">Back to dashboard</a>
        </div>

        @forelse ($sessions as $session)
            <article class="card card-loud">
                <div class="meta-stack" style="margin-bottom: 10px;">
                    <span class="meta-pill">{{ $session->day->name ?? 'Conference Day' }}</span>
                    <span class="meta-pill">{{ $session->start_time ?? 'TBD' }} - {{ $session->end_time ?? 'TBD' }}</span>
                    <span class="meta-pill">{{ $session->location ?? 'TBD' }}</span>
                </div>
                <h3 style="margin: 0 0 10px;">{{ $session->title }}</h3>
                <p class="muted" style="margin: 0 0 16px;">{{ $session->track->name ?? 'General Session' }}</p>
                <a href="/share/sessions/{{ $session->id }}/speaker" class="button">Open speaker graphic</a>
            </article>
        @empty
            <div class="card card-loud">
                <h3 style="margin-top: 0;">No linked speaking sessions yet</h3>
                <p class="muted" style="margin-bottom: 0;">We couldn’t find any sessions linked to this account yet. Once a session is connected to your user, your speaker graphics will show up here.</p>
            </div>
        @endforelse
    </section>
@endsection
