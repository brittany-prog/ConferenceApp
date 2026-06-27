@extends('layouts.app')

@section('title', 'Sponsors | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div class="admin-header">
            <div>
                <span class="eyebrow">Brand Partners</span>
                <h2 style="margin: 10px 0 0;">Sponsors</h2>
                <p class="lede" style="margin:0;">Manage up to twenty sponsor placements with logos, links, and descriptions.</p>
            </div>
            <div class="admin-header-actions">
                <a href="/admin/sponsors/export/interests" class="button secondary">Download Sponsor Interest CSV</a>
                @if ($sponsors->count() < 20)
                    <a href="/admin/sponsors/create" class="button">Add sponsor</a>
                @endif
            </div>
        </div>

        @forelse ($sponsors as $sponsor)
            <article class="card" style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                <div style="display:flex; gap:16px; align-items:flex-start; flex:1 1 560px;">
                    @if ($sponsor->logo_path)
                        <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" style="width:96px; height:96px; object-fit:contain; border-radius:16px; background:#fff;">
                    @endif
                    <div>
                        <p class="muted" style="margin:0;">#{{ $sponsor->display_order }} @if($sponsor->tier) | {{ $sponsor->tier }} @endif</p>
                        <h3 style="margin:8px 0;">{{ $sponsor->name }}</h3>
                        @if ($sponsor->headline)
                            <p style="margin:0 0 8px;"><strong>{{ $sponsor->headline }}</strong></p>
                        @endif
                        <p class="muted" style="margin:0 0 10px;">{{ $sponsor->description }}</p>
                        @if ($sponsor->booth_location)
                            <p class="muted" style="margin:0 0 10px;">Meet them at {{ $sponsor->booth_location }}</p>
                        @endif
                        @if ($sponsor->website_url)
                            <a href="{{ $sponsor->website_url }}" target="_blank" rel="noreferrer">{{ $sponsor->website_url }}</a>
                        @endif
                        <p class="muted" style="margin:10px 0 0;">Interested attendees: {{ $sponsor->interested_users_count }}</p>
                    </div>
                </div>
                <div class="admin-card-actions">
                    @if ($sponsor->interested_users_count > 0)
                        <a href="/admin/sponsors/{{ $sponsor->id }}/export-interests" class="button secondary admin-card-action-button">Export Leads</a>
                    @endif
                    <a href="/admin/sponsors/{{ $sponsor->id }}/edit" class="button secondary admin-card-action-button">Edit</a>
                    <form method="POST" action="/admin/sponsors/{{ $sponsor->id }}" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button secondary admin-card-action-button" onclick="return confirm('Delete this sponsor?')">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">No sponsors yet. Add your first partner brand to start shaping the homepage.</p>
        @endforelse
    </section>

    @push('page-styles')
        <style>
            .admin-header-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

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
