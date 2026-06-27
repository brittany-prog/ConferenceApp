@extends('layouts.app')

@section('title', 'Speakers | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
        <section class="panel stack speaker-directory-shell">
            <div class="section-heading">
            <div>
                <span class="eyebrow">People</span>
                <h2 style="margin: 10px 0 0;">Speakers</h2>
                <p class="muted">Meet the people shaping the agenda, browse their work fast, and jump from speakers into sessions more naturally.</p>
            </div>

            <form method="GET" action="/speakers" class="directory-controls">
                <div class="directory-control">
                    <label for="speaker-q">Search speakers</label>
                    <input id="speaker-q" type="text" name="q" value="{{ $query }}" placeholder="Search by name, title, organization, bio, or city">
                </div>
                <div class="directory-control directory-sort">
                    <label for="speaker-sort">Sort</label>
                    <select id="speaker-sort" name="sort">
                        <option value="az" @selected($sort === 'az')>A-Z</option>
                        <option value="za" @selected($sort === 'za')>Z-A</option>
                    </select>
                </div>
            </form>
        </div>

        <nav class="section-tabs" aria-label="People sections">
            <a href="/speakers" class="section-tab is-current">Speakers</a>
            <a href="/attendees" class="section-tab">Attendees</a>
            <a href="/sponsors" class="section-tab">Sponsors</a>
        </nav>

        <article class="card speaker-summary-card speaker-summary-card--guide">
            <p class="muted">Directory guide</p>
            <h3 style="margin:0 0 6px; letter-spacing:-0.03em;">Find the people behind the sessions</h3>
            <p class="muted" style="margin:0;">Browse speakers quickly, jump into the agenda from their profiles, and keep the directory feeling like a clean list instead of a stack of oversized profiles.</p>
        </article>

        <div class="speaker-list">
            @forelse ($speakers as $speaker)
                <article class="card card-loud speaker-card">
                    <div class="speaker-card-identity">
                        @if ($speaker->profile_photo_path)
                            <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" class="speaker-card-avatar">
                        @else
                            <div class="speaker-card-avatar speaker-card-avatar--fallback">
                                {{ strtoupper(substr($speaker->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="speaker-card-main">
                        <div class="speaker-card-header">
                            <div>
                                <span class="eyebrow">Speaker</span>
                                <h3 style="margin:8px 0 0;"><a href="/speakers/{{ $speaker->id }}">{{ $speaker->name }}</a></h3>
                            </div>
                            @if ($speaker->location)
                                <span class="meta-pill">{{ $speaker->location }}</span>
                            @endif
                        </div>
                        <div class="meta-stack">
                            @if ($speaker->title || $speaker->organization)
                                <span class="meta-pill">{{ $speaker->title }}@if($speaker->title && $speaker->organization) at @endif{{ $speaker->organization }}</span>
                            @endif
                        </div>
                        <p class="speaker-card-bio">{{ $speaker->bio ?: 'Speaker bio coming soon.' }}</p>
                    </div>
                    <div class="speaker-card-actions">
                        <a href="/speakers/{{ $speaker->id }}" class="button secondary">View Speaker</a>
                    </div>
                </article>
            @empty
                <p class="muted">No speakers have been added yet.</p>
            @endforelse
        </div>

        @if ($speakers->hasPages())
            @php
                $lastPage = $speakers->lastPage();
                $currentPage = $speakers->currentPage();
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
            <nav class="directory-pagination" aria-label="Speaker pages">
                @if ($speakers->onFirstPage())
                    <span class="pagination-link is-disabled">Previous</span>
                @else
                    <a href="{{ $speakers->previousPageUrl() }}" class="pagination-link">Previous</a>
                @endif

                @foreach ($paginationItems as $paginationItem)
                    @if ($paginationItem === 'gap')
                        <span class="pagination-gap" aria-hidden="true">…</span>
                    @elseif ($paginationItem === $speakers->currentPage())
                        <span class="pagination-link is-current" aria-current="page">{{ $paginationItem }}</span>
                    @else
                        <a href="{{ $speakers->url($paginationItem) }}" class="pagination-link">{{ $paginationItem }}</a>
                    @endif
                @endforeach

                @if ($speakers->hasMorePages())
                    <a href="{{ $speakers->nextPageUrl() }}" class="pagination-link">Next</a>
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

            .speaker-directory-shell {
                gap: 14px;
            }

            .speaker-summary-card {
                display: grid;
                gap: 6px;
                align-content: start;
                padding: 14px;
            }

            .speaker-summary-card--guide {
                background: var(--card-loud-bg);
            }

            .speaker-list {
                display: grid;
                gap: 10px;
            }

            .speaker-card {
                display: grid;
                grid-template-columns: 76px minmax(0, 1fr) minmax(132px, 168px);
                gap: 12px;
                align-items: start;
                padding: 14px;
            }

            .speaker-card-avatar {
                width: 76px;
                height: 76px;
                object-fit: cover;
                border-radius: 16px;
                margin-bottom: 0;
                box-shadow: 0 6px 12px rgba(17, 23, 48, 0.08);
            }

            .speaker-card-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.15rem;
                font-weight: 800;
            }

            .speaker-card-main {
                display: grid;
                gap: 6px;
                min-width: 0;
            }

            .speaker-card-header {
                display: flex;
                justify-content: space-between;
                gap: 8px;
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .speaker-card-bio {
                margin: 0;
                color: var(--brand-ink);
                line-height: 1.55;
            }

            .speaker-card-actions {
                display: grid;
                width: 100%;
                max-width: 160px;
            }

            @media (max-width: 900px) {
                .directory-controls {
                    width: 100%;
                    grid-template-columns: 1fr;
                }

                .speaker-card {
                    grid-template-columns: 76px minmax(0, 1fr);
                }

                .speaker-card-actions {
                    grid-column: 1 / -1;
                    max-width: none;
                }
            }

            @media (max-width: 720px) {
                .speaker-card {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }

                .speaker-card-identity {
                    display: flex;
                    justify-content: flex-start;
                }

                .directory-pagination {
                    justify-content: flex-start;
                }
            }
        </style>
    @endpush
@endsection