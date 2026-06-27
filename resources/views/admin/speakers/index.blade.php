@extends('layouts.app')

@section('title', 'Speakers | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <span class="eyebrow">Speaker Profiles</span>
                <h2 style="margin: 10px 0 0;">Speakers</h2>
                <p class="muted">Speaker profiles now come from users marked as speakers in the user directory.</p>
            </div>
            <a href="/admin/users" class="button">Manage Speaker Assignments</a>
        </div>

        @forelse($speakers as $speaker)
            <article class="card" style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                <div style="display:flex; gap:16px; align-items:flex-start;">
                    @if ($speaker->profile_photo_path)
                        <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" style="width:88px; height:88px; object-fit:cover; border-radius:24px; box-shadow:0 8px 18px rgba(17, 23, 48, 0.12);">
                    @endif
                    <div>
                        <h3 style="margin:0 0 8px;">{{ $speaker->name }}</h3>
                        <p class="muted" style="margin:0 0 8px;">{{ $speaker->title }} @if($speaker->organization) at {{ $speaker->organization }} @endif</p>
                        <p class="muted" style="margin:0;">{{ $speaker->bio }}</p>
                    </div>
                </div>
                <div class="admin-card-actions">
                    <a href="/admin/speakers/{{ $speaker->id }}/edit" class="button secondary admin-card-action-button">Edit</a>
                    <form method="POST" action="/admin/speakers/{{ $speaker->id }}" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button secondary admin-card-action-button" onclick="return confirm('Remove speaker designation?')">Remove</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">No speaker users yet. Mark attendees as speakers from the user directory.</p>
        @endforelse
    </section>

    @push('page-styles')
        <style>
            .admin-card-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .admin-card-action-button {
                min-height: 42px;
                justify-content: center;
            }
        </style>
    @endpush
@endsection
