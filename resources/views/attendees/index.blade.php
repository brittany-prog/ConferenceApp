@extends('layouts.app')

@section('title', 'Attendees | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
        <section class="panel stack attendee-directory-shell">
        <div class="section-heading">
            <div>
                <span class="eyebrow">People</span>
                <h2 style="margin: 10px 0 0;">Attendees</h2>
                <p class="muted">Browse the room, spot shared interests, and reach out to the people you want to meet before the hallways get busy.</p>
            </div>

            <form method="GET" action="/attendees" class="directory-controls">
                <div class="directory-control">
                    <label for="q">Search attendees</label>
                    <input id="q" type="text" name="q" value="{{ $query }}" placeholder="Search by name, title, organization, interests, or city">
                </div>
                <div class="directory-control directory-sort">
                    <label for="sort">Sort</label>
                    <select id="sort" name="sort">
                        <option value="az" @selected($sort === 'az')>A-Z</option>
                        <option value="za" @selected($sort === 'za')>Z-A</option>
                    </select>
                </div>
            </form>
        </div>

        <nav class="section-tabs" aria-label="People sections">
            <a href="/speakers" class="section-tab">Speakers</a>
            <a href="/attendees" class="section-tab is-current">Attendees</a>
            <a href="/sponsors" class="section-tab">Sponsors</a>
        </nav>

        <div class="attendee-summary-grid">
            <article class="card attendee-summary-card">
                <p class="muted">Results</p>
                <h3 class="metric-number">{{ $attendees->total() }}</h3>
                <p class="muted" style="margin:0;">Attendees available to browse</p>
            </article>
            <article class="card attendee-summary-card">
                <p class="muted">Current page</p>
                <h3 class="metric-number">{{ $attendees->count() }}</h3>
                <p class="muted" style="margin:0;">Profiles in this view</p>
            </article>
            <article class="card attendee-summary-card attendee-summary-card--guide">
                <p class="muted">Best next move</p>
                <h3 style="margin:0 0 8px; letter-spacing:-0.03em;">Start a few warm conversations</h3>
                <p class="muted" style="margin:0;">Use profiles, interests, and organizations to figure out who you want to meet while there is still time to connect.</p>
            </article>
        </div>

        <div class="attendee-list">
            @forelse ($attendees as $attendee)
                <article class="card card-loud attendee-card attendee-card-shell">
                    <div class="attendee-card-identity">
                        @if ($attendee->profile_photo_path)
                            <img src="{{ asset('storage/'.$attendee->profile_photo_path) }}" alt="{{ $attendee->name }} profile photo" class="attendee-card-avatar">
                        @else
                            <div class="attendee-card-avatar attendee-card-avatar--fallback">
                                {{ strtoupper(substr($attendee->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="attendee-card-main">
                        <div class="attendee-card-header">
                            <div>
                                <h3 style="margin:0 0 6px;">{{ $attendee->name }}</h3>
                                @if ($attendee->title || $attendee->organization)
                                    <p class="muted" style="margin:0;">
                                        {{ $attendee->title }}@if($attendee->title && $attendee->organization) at @endif{{ $attendee->organization }}
                                    </p>
                                @endif
                            </div>
                            @if ($attendee->location)
                                <span class="meta-pill">{{ $attendee->location }}</span>
                            @endif
                        </div>
                        <div class="meta-stack" style="margin-bottom:2px;">
                            @if ($attendee->is_speaker)
                                <span class="eyebrow">Speaker</span>
                            @endif
                            @if ($attendee->is_exhibitor)
                                <span class="eyebrow">Exhibitor</span>
                            @endif
                            @if ($attendee->is_admin)
                                <span class="eyebrow">Admin</span>
                            @endif
                        </div>
                        @if ($attendee->interests)
                            <div class="meta-pill attendee-card-interest"><strong>Interests:</strong> {{ $attendee->interests }}</div>
                        @endif
                        <p class="muted attendee-card-bio">
                            {{ $attendee->bio ?: 'This attendee has not added a bio yet.' }}
                        </p>
                    </div>
                    <div class="attendee-card-actions">
                        <a href="/attendees/{{ $attendee->id }}" class="button secondary attendee-card-button">View Profile</a>
                        @if (auth()->id() !== $attendee->id)
                            <a href="/messages/{{ $attendee->id }}" class="button secondary attendee-card-button">Message</a>
                        @endif
                    </div>
                </article>
            @empty
                <p class="muted">No attendees matched your search.</p>
            @endforelse
        </div>

        @if ($attendees->hasPages())
            @php
                $lastPage = $attendees->lastPage();
                $currentPage = $attendees->currentPage();
                $paginationItems = collect(range(max(1, $currentPage - 1), min($lastPage, $currentPage + 1)));

                if (! $paginationItems->contains(1)) {
                    $paginationItems->prepend(1);

                    if ($currentPage > 3) {
                        $paginationItems->splice(1, 0, ['gap']);
                    }
                }

                if (! $paginationItems->contains($lastPage)) {
                    if ($currentPage < $lastPage - 2) {
                        $paginationItems->push('gap');
                    }

                    $paginationItems->push($lastPage);
                }
            @endphp
            <nav class="directory-pagination" aria-label="Attendee pages">
                @if ($attendees->onFirstPage())
                    <span class="pagination-link is-disabled">Previous</span>
                @else
                    <a href="{{ $attendees->previousPageUrl() }}" class="pagination-link">Previous</a>
                @endif

                @foreach ($paginationItems as $paginationItem)
                    @if ($paginationItem === 'gap')
                        <span class="pagination-gap" aria-hidden="true">…</span>
                    @elseif ($paginationItem === $attendees->currentPage())
                        <span class="pagination-link is-current" aria-current="page">{{ $paginationItem }}</span>
                    @else
                        <a href="{{ $attendees->url($paginationItem) }}" class="pagination-link">{{ $paginationItem }}</a>
                    @endif
                @endforeach

                @if ($attendees->hasMorePages())
                    <a href="{{ $attendees->nextPageUrl() }}" class="pagination-link">Next</a>
                @else
                    <span class="pagination-link is-disabled">Next</span>
                @endif
            </nav>
        @endif
    </section>
    </div>

    @push('page-styles')
        <style>
            .directory-pagination {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
                padding-top: 10px;
            }

            .pagination-link {
                min-width: 44px;
                padding: 10px 14px;
                border-radius: 999px;
                border: 1px solid var(--brand-line);
                background: rgba(255, 255, 255, 0.86);
                color: var(--brand-ink);
                font-weight: 700;
                text-align: center;
                text-decoration: none;
            }

            .pagination-link.is-current {
                background: var(--button-gradient);
                color: #fff;
                border-color: transparent;
            }

            .pagination-link.is-disabled {
                opacity: 0.45;
                pointer-events: none;
            }

            .pagination-gap {
                color: var(--brand-muted);
                font-weight: 700;
                padding: 0 2px;
            }

            .directory-controls {
                width: min(520px, 100%);
                display: grid;
                grid-template-columns: minmax(0, 1fr) 140px;
                gap: 12px;
                align-items: end;
            }

            .directory-control {
                min-width: 0;
            }

            .attendee-directory-shell {
                gap: 18px;
            }

            .attendee-summary-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
            }

            .attendee-summary-card {
                display: grid;
                gap: 8px;
                align-content: start;
                padding: 18px;
            }

            .attendee-summary-card--guide {
                background: var(--card-loud-bg);
            }

            .attendee-list {
                display: grid;
                gap: 14px;
            }

            .attendee-card-actions {
                display: grid;
                gap: 10px;
                width: 100%;
                max-width: 220px;
            }

            .attendee-card-shell {
                display: grid;
                grid-template-columns: 88px minmax(0, 1fr) minmax(180px, 220px);
                gap: 18px;
                align-items: start;
                backdrop-filter: blur(10px);
                padding: 20px;
            }

            .attendee-card-avatar {
                width: 88px;
                height: 88px;
                object-fit: cover;
                border-radius: 24px;
                margin-bottom: 14px;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
            }

            .attendee-card-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.5rem;
                font-weight: 800;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
            }

            .attendee-card-main {
                display: grid;
                gap: 10px;
                min-width: 0;
            }

            .attendee-card-header {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .attendee-card-interest {
                width: fit-content;
                max-width: 100%;
            }

            .attendee-card-bio {
                margin: 0;
                line-height: 1.55;
            }

            .attendee-card-button {
                min-height: 48px;
                justify-content: center;
                text-align: center;
                width: 100%;
            }

            @media (max-width: 900px) {
                .directory-controls {
                    width: 100% !important;
                    grid-template-columns: 1fr;
                }

                .attendee-summary-grid {
                    grid-template-columns: 1fr;
                }

                .attendee-card-shell {
                    grid-template-columns: 88px minmax(0, 1fr);
                }

                .attendee-card-actions {
                    grid-column: 1 / -1;
                    max-width: none;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 720px) {
                .attendee-card-shell {
                    grid-template-columns: 1fr;
                    gap: 16px;
                }

                .attendee-card-identity {
                    display: flex;
                    justify-content: flex-start;
                }

                .attendee-card-actions {
                    grid-template-columns: 1fr;
                }

                .directory-pagination {
                    justify-content: flex-start;
                }
            }
        </style>
    @endpush
@endsection
