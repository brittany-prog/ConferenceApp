@extends('layouts.app')

@section('title', $appSettings['brand_name'].' | Home')

@section('content')
    <div class="dark-page-shell">
        <section class="panel card-loud dashboard-stage">
            @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                <a href="/admin/settings" class="admin-edit-chip" aria-label="Edit homepage content">
                    <span aria-hidden="true">✎</span>
                    <span>Edit</span>
                </a>
            @endif

            <div class="dashboard-stage__hero">
                <div class="dashboard-stage__intro">
                    <span class="page-kicker">{{ $appSettings['homepage_subtitle'] }}</span>
                    <h2 class="page-title dashboard-stage__title">{{ $appSettings['brand_name'] }}</h2>
                    <p class="page-subtitle dashboard-stage__subtitle">{{ $appSettings['homepage_description'] }}</p>

                    <div class="dashboard-stage__stats">
                        <article class="dashboard-stage__stat">
                            <span>Today</span>
                            <strong>{{ $todayLabel }}</strong>
                            <small>{{ $sparkCountdownLabel ?: $appSettings['brand_name'].' is on the horizon.' }}</small>
                        </article>
                    </div>
                </div>

                <aside class="dashboard-stage__aside">
                    <div class="dashboard-pulse-card">
                        <span class="eyebrow">Live pulse</span>
                        @if ($liveSessions->isNotEmpty())
                            <h3>Happening now</h3>
                            @foreach ($liveSessions->take(2) as $liveSession)
                                <a href="/sessions/{{ $liveSession->id }}" class="dashboard-pulse-card__item">
                                    <strong>{{ $liveSession->title }}</strong>
                                    <span>{{ $liveSession->track->name ?? 'General Session' }} · {{ $liveSession->location ?? 'TBD' }}</span>
                                </a>
                            @endforeach
                        @elseif ($nextSavedSession)
                            <h3>Up next for you</h3>
                            <a href="/sessions/{{ $nextSavedSession->id }}" class="dashboard-pulse-card__item">
                                <strong>{{ $nextSavedSession->title }}</strong>
                                <span>{{ $nextSavedSession->day->name ?? 'Conference Day' }} · {{ $nextSavedSession->start_time }} - {{ $nextSavedSession->end_time }}</span>
                            </a>
                        @else
                            <h3>Build your plan</h3>
                            <p class="muted" style="margin:0;">Nothing is live right now. Save a few sessions and this space will start working like your conference control center.</p>
                        @endif
                    </div>

                    <div class="dashboard-stage__mini-grid">
                        <article class="card dashboard-mini-card">
                            <span class="muted">Saved</span>
                            <strong>{{ $savedSessionsCount }}</strong>
                        </article>
                        <article class="card dashboard-mini-card">
                            <span class="muted">Unread</span>
                            <strong>{{ $unreadMessagesCount }}</strong>
                        </article>
                    </div>
                </aside>
            </div>

            <div class="dashboard-action-grid" aria-label="Dashboard quick actions">
                <a href="{{ $appSettings['homepage_primary_cta_link'] ?: '/sessions' }}" class="dashboard-action-tile dashboard-action-tile--primary">
                    <span class="dashboard-action-tile__eyebrow">Agenda</span>
                    <strong>{{ $appSettings['homepage_primary_cta_label'] ?: 'Browse Sessions' }}</strong>
                    <span>Explore the full schedule, rooms, and what is happening next.</span>
                </a>
                <a href="/my-schedule" class="dashboard-action-tile">
                    <span class="dashboard-action-tile__eyebrow">Plan</span>
                    <strong>My Schedule</strong>
                    <span>Keep your saved sessions together in one place.</span>
                </a>
                <a href="/attendees" class="dashboard-action-tile">
                    <span class="dashboard-action-tile__eyebrow">People</span>
                    <strong>Meet People</strong>
                    <span>Browse attendees, speakers, and partners.</span>
                </a>
                <a href="/community" class="dashboard-action-tile">
                    <span class="dashboard-action-tile__eyebrow">Community</span>
                    <strong>Join the Conversation</strong>
                    <span>Introduce yourself and jump into active topics.</span>
                </a>
                <a href="/messages" class="dashboard-action-tile">
                    <span class="dashboard-action-tile__eyebrow">Inbox</span>
                    <strong>Messages</strong>
                    <span>Reply quickly and keep new connections moving.</span>
                </a>
                @if ($speakerShareSession)
                    <a href="/share/sessions/{{ $speakerShareSession->id }}/speaker" class="dashboard-action-tile">
                        <span class="dashboard-action-tile__eyebrow">Share</span>
                        <strong>Promote My Session</strong>
                        <span>Download a speaker graphic and share your talk.</span>
                    </a>
                @elseif (auth()->user()->is_speaker || auth()->user()->is_admin)
                    <a href="/share/speaking" class="dashboard-action-tile">
                        <span class="dashboard-action-tile__eyebrow">Share</span>
                        <strong>Open Speaker Graphics</strong>
                        <span>Choose a session and download your promo graphic.</span>
                    </a>
                @else
                    <a href="/share/attending" class="dashboard-action-tile">
                        <span class="dashboard-action-tile__eyebrow">Share</span>
                        <strong>Download & Share Your Social Graphic</strong>
                        <span>Post that you are attending and invite others in.</span>
                    </a>
                @endif
            </div>
        </section>

        <section class="dashboard-content-grid">
            <section class="panel stack">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Your Event</span>
                        <h3 style="margin: 12px 0 0; font-size:1.3rem; font-weight:700; letter-spacing:-0.03em;">Today and next up</h3>
                    </div>
                    <a href="/my-schedule">View full schedule</a>
                </div>

                <div class="dashboard-feed-list">
                    @forelse ($todaysSavedSessions as $session)
                        <article class="card dashboard-feed-card">
                            <div class="dashboard-feed-card__time">
                                <strong>{{ $session->start_time }}</strong>
                                <span>{{ $session->end_time }}</span>
                            </div>
                            <div class="dashboard-feed-card__copy">
                                <h4><a href="/sessions/{{ $session->id }}">{{ $session->title }}</a></h4>
                                <p>{{ $session->track->name ?? 'General Session' }} · {{ $session->location ?? 'TBD' }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="card">
                            <p class="muted" style="margin:0;">You have no saved sessions for today yet. Start by browsing the agenda and starring the sessions you do not want to miss.</p>
                        </div>
                    @endforelse
                </div>

                <div class="dashboard-subsection">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">Updates</span>
                            <h4 style="margin: 12px 0 0; font-size:1.1rem;">Latest announcements</h4>
                        </div>
                        <a href="/announcements">View all</a>
                    </div>

                    <div class="dashboard-feed-list">
                        @forelse ($announcements->take(3) as $announcement)
                            <article class="card dashboard-note-card">
                                <h4>{{ $announcement->title }}</h4>
                                <p>{{ $announcement->message }}</p>
                            </article>
                        @empty
                            <div class="card">
                                <p class="muted" style="margin:0;">No announcements have been posted yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="panel stack">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Community</span>
                        <h3 style="margin: 12px 0 0; font-size:1.3rem; font-weight:700; letter-spacing:-0.03em;">People and momentum</h3>
                    </div>
                    <a href="/community">Open community</a>
                </div>

                <div class="dashboard-community-strip">
                    <article class="card dashboard-community-stat">
                        <span class="muted">Points</span>
                        <strong>{{ $sparkSummary['points'] }}</strong>
                    </article>
                    <article class="card dashboard-community-stat">
                        <span class="muted">Badges</span>
                        <strong>{{ $sparkSummary['badge_count'] }}</strong>
                    </article>
                    <article class="card dashboard-community-stat">
                        <span class="muted">Next badge</span>
                        <strong>{{ $sparkSummary['next_badge']->name ?? 'All caught up' }}</strong>
                    </article>
                </div>

                <div class="dashboard-feed-list">
                    @forelse ($communityTopics->take(4) as $topic)
                        <article class="card dashboard-topic-card">
                            <div class="dashboard-topic-card__meta">
                                <span>{{ $topic->published_posts_count }} {{ \Illuminate\Support\Str::plural('post', $topic->published_posts_count) }}</span>
                                @if($topic->is_intro)
                                    <span>Start here</span>
                                @endif
                            </div>
                            <h4><a href="/community/topics/{{ $topic->slug }}">{{ $topic->title }}</a></h4>
                            <p>{{ $topic->description ?: $topic->prompt }}</p>
                        </article>
                    @empty
                        <div class="card">
                            <p class="muted" style="margin:0;">Community discussions are on the way.</p>
                        </div>
                    @endforelse
                </div>

                <article class="card dashboard-community-partner-card">
                    <div class="dashboard-community-partner-card__copy">
                        <span class="meta-pill">New</span>
                        <h4>{{ $appSettings['community_external_heading'] }}</h4>
                        <p>{{ $appSettings['community_external_description'] }}</p>
                    </div>
                    @if ($appSettings['has_external_community_link'])
                        <div class="dashboard-community-partner-card__action">
                            <a href="{{ $appSettings['community_external_url'] }}" class="button primary" target="_blank" rel="noopener noreferrer">{{ $appSettings['community_external_cta_label'] ?: 'Open Community Space' }}</a>
                        </div>
                    @endif
                </article>
            </section>

            @if ($sponsors->isNotEmpty())
                <section class="panel stack">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">Sponsors</span>
                            <h3 style="margin: 12px 0 0; font-size:1.3rem; font-weight:700; letter-spacing:-0.03em;">Partners behind {{ $appSettings['brand_name'] }}</h3>
                        </div>
                        <a href="/sponsors">View all partners</a>
                    </div>

                    <div class="dashboard-sponsor-featured">
                        @foreach ($sponsors->take(2) as $sponsor)
                            <article class="card dashboard-sponsor-featured__card">
                                <div class="dashboard-sponsor-featured__logo">
                                    @if ($sponsor->logo_path)
                                        <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo">
                                    @else
                                        <span>{{ $sponsor->name }}</span>
                                    @endif
                                </div>
                                <div class="dashboard-sponsor-featured__copy">
                                    @if ($sponsor->tier)
                                        <span class="meta-pill">{{ $sponsor->tier }}</span>
                                    @endif
                                    <h4><a href="/sponsors/{{ $sponsor->id }}">{{ $sponsor->name }}</a></h4>
                                    <p>{{ $sponsor->headline ?: \Illuminate\Support\Str::limit($sponsor->description, 100) }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="dashboard-sponsor-logo-wall">
                        @foreach ($sponsors->skip(2) as $sponsor)
                            <a href="/sponsors/{{ $sponsor->id }}" class="card dashboard-sponsor-logo-chip">
                                @if ($sponsor->logo_path)
                                    <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo">
                                @else
                                    <span>{{ $sponsor->name }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </section>
    </div>
@endsection

<style>
        .dashboard-stage,
        .dashboard-stage__hero,
        .dashboard-stage__intro,
        .dashboard-stage__aside,
        .dashboard-pulse-card,
        .dashboard-content-grid,
        .dashboard-feed-list,
        .dashboard-subsection {
            display: grid;
        }

        .dashboard-stage {
            gap: 18px;
        }

        .dashboard-stage__hero {
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.82fr);
            gap: 18px;
            align-items: start;
        }

        .dashboard-stage__intro {
            gap: 10px;
        }

        .dashboard-stage__title,
        .dashboard-stage__subtitle {
            margin-bottom: 0;
        }

        .dashboard-stage__subtitle {
            max-width: 700px;
        }

        .dashboard-stage__stats {
            display: grid;
            grid-template-columns: minmax(0, 280px);
            gap: 0;
        }

        .dashboard-stage__stat,
        .dashboard-mini-card {
            padding: 16px;
            border-radius: 18px;
            border: 1px solid var(--brand-line);
            background: var(--stat-bg);
            display: grid;
            gap: 4px;
        }

        .dashboard-stage__stat span,
        .dashboard-mini-card span {
            color: var(--brand-muted);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-stage__stat strong,
        .dashboard-mini-card strong {
            font-size: clamp(1.1rem, 2vw, 1.8rem);
            line-height: 1.05;
            letter-spacing: -0.05em;
        }

        .dashboard-stage__stat small {
            color: var(--brand-muted);
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .dashboard-stage__aside,
        .dashboard-pulse-card {
            gap: 12px;
        }

        .dashboard-pulse-card {
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(12, 19, 52, 0.98), rgba(19, 33, 73, 0.96));
            color: #fff8ed;
            box-shadow: 0 20px 48px rgba(6, 10, 27, 0.28);
        }

        .dashboard-pulse-card .eyebrow,
        .dashboard-pulse-card .muted {
            color: rgba(255, 248, 237, 0.78);
        }

        .dashboard-pulse-card h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .dashboard-pulse-card__item {
            display: grid;
            gap: 4px;
            padding: 12px 13px;
            border-radius: 14px;
            background: rgba(255, 248, 237, 0.08);
            color: #fff8ed;
            text-decoration: none;
            border: 1px solid rgba(255, 248, 237, 0.08);
        }

        .dashboard-pulse-card__item span {
            color: rgba(255, 248, 237, 0.72);
            font-size: 0.92rem;
        }

        .dashboard-stage__mini-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .dashboard-action-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .dashboard-action-tile {
            display: grid;
            gap: 6px;
            align-content: start;
            min-height: 132px;
            padding: 16px;
            text-decoration: none;
            border: 1px solid var(--brand-line);
            background: var(--card-bg);
            color: var(--brand-ink);
            border-radius: 20px;
            box-shadow: 0 10px 28px rgba(15, 18, 42, 0.05);
        }

        .dashboard-action-tile strong {
            font-size: 1.05rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .dashboard-action-tile span:last-child {
            color: var(--brand-muted);
            line-height: 1.5;
        }

        .dashboard-action-tile__eyebrow {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #8a2844;
        }

        .dashboard-action-tile--primary {
            background: var(--button-gradient);
            color: #fff;
            border-color: transparent;
            box-shadow: var(--button-shadow);
        }

        .dashboard-action-tile--primary .dashboard-action-tile__eyebrow,
        .dashboard-action-tile--primary span:last-child {
            color: rgba(255, 255, 255, 0.82);
        }

        .dashboard-content-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            align-items: start;
        }

        .dashboard-feed-list,
        .dashboard-subsection {
            gap: 10px;
        }

        .dashboard-feed-card,
        .dashboard-note-card,
        .dashboard-topic-card,
        .dashboard-sponsor-featured__card {
            display: grid;
            gap: 10px;
        }

        .dashboard-feed-card {
            grid-template-columns: auto minmax(0, 1fr);
            align-items: start;
            gap: 12px;
        }

        .dashboard-feed-card__time {
            min-width: 78px;
            display: grid;
            gap: 6px;
        }

        .dashboard-feed-card__time strong {
            font-size: 1rem;
            letter-spacing: -0.03em;
        }

        .dashboard-feed-card__time span,
        .dashboard-feed-card__copy p,
        .dashboard-note-card p,
        .dashboard-topic-card p,
        .dashboard-sponsor-featured__copy p {
            margin: 0;
            color: var(--brand-muted);
        }

        .dashboard-feed-card__copy {
            display: grid;
            gap: 6px;
        }

        .dashboard-feed-card__copy h4,
        .dashboard-note-card h4,
        .dashboard-topic-card h4,
        .dashboard-sponsor-featured__copy h4 {
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .dashboard-community-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .dashboard-community-stat {
            display: grid;
            gap: 6px;
            padding: 14px;
        }

        .dashboard-community-stat strong {
            font-size: 1.12rem;
            line-height: 1.15;
            letter-spacing: -0.03em;
        }

        .dashboard-topic-card__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--brand-muted);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .dashboard-community-partner-card {
            display: grid;
            gap: 14px;
            align-content: start;
        }

        .dashboard-community-partner-card__copy {
            display: grid;
            gap: 8px;
        }

        .dashboard-community-partner-card__action {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .dashboard-community-partner-card__action .button {
            white-space: nowrap;
            width: 100%;
            justify-content: center;
        }

        .dashboard-community-partner-card__copy h4,
        .dashboard-community-partner-card__copy p {
            margin: 0;
        }

        .dashboard-community-partner-card__copy p {
            color: var(--brand-muted);
            line-height: 1.55;
        }

        .dashboard-sponsor-featured {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .dashboard-sponsor-featured__card {
            grid-template-columns: 1fr;
            align-content: start;
            gap: 14px;
            padding: 18px;
        }

        .dashboard-sponsor-featured__logo {
            min-height: 112px;
            display: grid;
            place-items: center;
            padding: 18px;
            border-radius: 16px;
            background: var(--stat-bg);
            border: 1px solid var(--brand-line);
        }

        .dashboard-sponsor-featured__logo img {
            width: 100%;
            max-height: 78px;
            object-fit: contain;
        }

        .dashboard-sponsor-featured__copy {
            display: grid;
            gap: 8px;
            align-content: start;
        }

        .dashboard-sponsor-logo-wall {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .dashboard-sponsor-logo-chip {
            min-height: 88px;
            display: grid;
            place-items: center;
            padding: 14px;
            text-decoration: none;
        }

        .dashboard-sponsor-logo-chip img {
            width: 100%;
            max-height: 58px;
            object-fit: contain;
        }

        @media (max-width: 1080px) {
            .dashboard-stage__hero,
            .dashboard-content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
            .dashboard-stage__stats,
            .dashboard-community-strip,
            .dashboard-sponsor-logo-wall,
            .dashboard-action-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 720px) {
            .dashboard-stage {
                gap: 14px;
            }

            .dashboard-stage__hero,
            .dashboard-content-grid {
                gap: 12px;
            }

            .dashboard-pulse-card,
            .dashboard-action-tile,
            .dashboard-feed-card,
            .dashboard-note-card,
            .dashboard-topic-card,
            .dashboard-sponsor-featured__card,
            .dashboard-community-stat,
            .dashboard-mini-card,
            .dashboard-stage__stat {
                padding: 14px;
            }

            .dashboard-action-tile {
                min-height: auto;
                gap: 5px;
            }

            .dashboard-feed-list,
            .dashboard-subsection,
            .dashboard-community-partner-card,
            .dashboard-sponsor-featured,
            .dashboard-sponsor-logo-wall {
                gap: 8px;
            }

            .dashboard-stage__stats,
            .dashboard-stage__mini-grid,
            .dashboard-community-strip,
            .dashboard-action-grid,
            .dashboard-sponsor-logo-wall,
            .dashboard-sponsor-featured__card,
            .dashboard-community-partner-card {
                grid-template-columns: 1fr;
            }

            .dashboard-sponsor-featured {
                grid-template-columns: 1fr;
            }

            .dashboard-feed-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
