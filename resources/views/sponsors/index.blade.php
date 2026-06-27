@extends('layouts.app')

@section('title', 'Sponsors & Exhibitors | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
        <section class="panel stack sponsors-directory-shell">
            <div class="sponsors-directory-intro">
            <span class="eyebrow">Partners</span>
            <h2 style="margin: 10px 0 0;">Sponsors & Exhibitors</h2>
            <p class="lede" style="margin:0;">Explore the organizations supporting {{ $appSettings['brand_name'] }}. Exhibitor partners include the on-site teams you can meet and message during the event.</p>
        </div>

        <nav class="section-tabs" aria-label="People sections">
            <a href="/speakers" class="section-tab">Speakers</a>
            <a href="/attendees" class="section-tab">Attendees</a>
            <a href="/sponsors" class="section-tab is-current">Sponsors</a>
        </nav>

        <div class="sponsor-summary-grid">
            <article class="card sponsor-summary-card">
                <p class="muted">Organizations</p>
                <h3 class="metric-number">{{ $sponsors->count() }}</h3>
                <p class="muted" style="margin:0;">Sponsors in this directory</p>
            </article>
            <article class="card sponsor-summary-card">
                <p class="muted">Exhibitor partners</p>
                <h3 class="metric-number">{{ $sponsors->where('exhibitors_count', '>', 0)->count() }}</h3>
                <p class="muted" style="margin:0;">Groups with on-site reps</p>
            </article>
            <article class="card sponsor-summary-card sponsor-summary-card--guide">
                <p class="muted">Best next move</p>
                <h3 style="margin:0 0 8px; letter-spacing:-0.03em;">Find the booths you want to visit</h3>
                <p class="muted" style="margin:0;">Use sponsor profiles to shortlist the organizations and exhibitor teams you want to meet while you’re on site.</p>
            </article>
        </div>

        <div class="sponsors-directory-grid">
            @forelse ($sponsors as $sponsor)
                <article class="card card-loud sponsor-directory-card">
                    <div class="sponsor-directory-card__media">
                        @if ($sponsor->logo_path)
                            <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" class="sponsor-directory-card__logo">
                        @else
                            <div class="sponsor-directory-card__logo sponsor-directory-card__logo--fallback">
                                {{ strtoupper(substr($sponsor->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="sponsor-directory-card__body">
                        <div class="sponsor-directory-card__header">
                            <div class="meta-stack">
                                @if ($sponsor->tier)
                                    <span class="eyebrow">{{ $sponsor->tier }}</span>
                                @endif
                                @if ($sponsor->exhibitors_count > 0)
                                    <span class="meta-pill">Exhibitor partner</span>
                                @endif
                            </div>
                            <h3 style="margin:0;"><a href="/sponsors/{{ $sponsor->id }}">{{ $sponsor->name }}</a></h3>
                            @if ($sponsor->headline)
                                <p class="muted" style="margin:0;">{{ $sponsor->headline }}</p>
                            @endif
                        </div>

                        @if ($sponsor->description)
                            <p class="sponsor-directory-card__description">{{ \Illuminate\Support\Str::limit($sponsor->description, 180) }}</p>
                        @endif

                        <div class="sponsor-directory-card__details">
                            @if ($sponsor->booth_location)
                                <span class="meta-pill">Find them at {{ $sponsor->booth_location }}</span>
                            @endif
                            @if ($sponsor->exhibitors_count > 0)
                                <span class="meta-pill">{{ $sponsor->exhibitors_count }} exhibitor {{ \Illuminate\Support\Str::plural('rep', $sponsor->exhibitors_count) }}</span>
                            @endif
                        </div>

                        <div class="sponsor-directory-card__actions">
                            <a href="/sponsors/{{ $sponsor->id }}" class="button secondary sponsor-directory-card__button">View profile</a>
                            @if ($sponsor->website_url)
                                <a href="{{ $sponsor->website_url }}" target="_blank" rel="noreferrer" class="button secondary sponsor-directory-card__button">Website</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <section class="card">
                    <p class="muted" style="margin:0;">No sponsors or exhibitors are available yet.</p>
                </section>
            @endforelse
        </div>
    </section>

    </div>

    @push('page-styles')
        <style>
            .sponsors-directory-shell {
                gap: 22px;
            }

            .sponsor-summary-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
            }

            .sponsor-summary-card {
                display: grid;
                gap: 8px;
                align-content: start;
                padding: 18px;
            }

            .sponsor-summary-card--guide {
                background: var(--card-loud-bg);
            }

            .sponsors-directory-grid {
                display: grid;
                gap: 14px;
            }

            .sponsor-directory-card {
                display: grid;
                grid-template-columns: 140px minmax(0, 1fr) minmax(160px, 220px);
                gap: 20px;
                align-items: start;
                padding: 22px;
                min-height: 100%;
                backdrop-filter: blur(10px);
            }

            .sponsor-directory-card__media {
                display: grid;
                place-items: center;
            }

            .sponsor-directory-card__logo {
                width: 100%;
                max-width: 140px;
                max-height: 104px;
                object-fit: contain;
            }

            .sponsor-directory-card__logo--fallback {
                aspect-ratio: 1;
                border-radius: 28px;
                background: var(--button-gradient);
                color: #fff;
                font-size: 2rem;
                font-weight: 800;
                box-shadow: var(--button-shadow);
            }

            .sponsor-directory-card__body,
            .sponsor-directory-card__header {
                display: grid;
                gap: 10px;
            }

            .sponsor-directory-card__actions {
                align-content: start;
            }

            .sponsor-directory-card__header h3 {
                line-height: 1.1;
                letter-spacing: -0.02em;
            }

            .sponsor-directory-card__description {
                margin: 0;
            }

            .sponsor-directory-card__details,
            .sponsor-directory-card__actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .sponsor-directory-card__button {
                min-height: 46px;
                justify-content: center;
            }

            @media (max-width: 900px) {
                .sponsor-summary-grid {
                    grid-template-columns: 1fr;
                }

                .sponsor-directory-card {
                    grid-template-columns: 140px minmax(0, 1fr);
                }

                .sponsor-directory-card__actions {
                    grid-column: 1 / -1;
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 720px) {
                .sponsor-directory-card {
                    grid-template-columns: 1fr;
                    gap: 16px;
                    padding: 18px;
                }

                .sponsor-directory-card__media {
                    justify-items: start;
                }

                .sponsor-directory-card__logo {
                    max-width: 180px;
                }

                .sponsor-directory-card__actions {
                    grid-template-columns: 1fr;
                }

                .sponsor-directory-card__button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
