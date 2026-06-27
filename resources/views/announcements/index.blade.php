@extends('layouts.app')

@section('title', 'Announcements | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <span class="eyebrow">Updates</span>
        <h2 style="margin: 10px 0 0;">Announcements</h2>

        @forelse ($announcements as $announcement)
            <article class="card">
                <h3 style="margin-top: 0;">{{ $announcement->title }}</h3>
                <p class="muted" style="margin-bottom: 0;">{{ $announcement->message }}</p>
            </article>
        @empty
            <p class="muted">No announcements have been posted yet.</p>
        @endforelse
    </section>
@endsection
