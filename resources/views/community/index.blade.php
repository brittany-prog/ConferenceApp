@extends('layouts.app')

@section('title', 'Community | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
        <section class="panel card-loud community-hub">
            @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                <a href="/admin/settings" class="admin-edit-chip" aria-label="Edit community page intro">
                    <span aria-hidden="true">✎</span>
                    <span>Edit</span>
                </a>
            @endif

            <div class="community-hub__header">
                <div class="community-hub__intro">
                    <span class="page-kicker">Community</span>
                    <h2 class="page-title community-hub__title">{{ $appSettings['community_page_title'] }}</h2>
                    <p class="page-subtitle community-hub__subtitle">{{ $appSettings['community_page_description'] }}</p>
                </div>

                <div class="community-hub__score">
                    <article class="card community-score-card">
                        <span class="muted">Points</span>
                        <strong>{{ $sparkSummary['points'] }}</strong>
                    </article>
                    <article class="card community-score-card">
                        <span class="muted">Badges</span>
                        <strong>{{ $sparkSummary['badge_count'] }}</strong>
                    </article>
                    <article class="card community-score-card community-score-card--wide">
                        <span class="muted">Next badge</span>
                        <strong>{{ $sparkSummary['next_badge']->name ?? 'You are all caught up' }}</strong>
                    </article>
                </div>
            </div>

            <div class="community-lane-toggle" role="tablist" aria-label="Community lanes">
                <button type="button" class="community-lane-toggle__button is-active" data-community-lane="people" role="tab" aria-selected="true">Meet People</button>
                <button type="button" class="community-lane-toggle__button" data-community-lane="topics" role="tab" aria-selected="false">Discussion Topics</button>
            </div>

            @if ($appSettings['has_external_community_link'])
                <article class="card card-loud community-partner-card">
                    <div class="community-partner-card__copy">
                        <span class="eyebrow">Community Extension</span>
                        <h3>{{ $appSettings['community_external_heading'] }}</h3>
                        <p>{{ $appSettings['community_external_description'] }}</p>
                    </div>
                    <div class="community-partner-card__actions">
                        <a href="{{ $appSettings['community_external_url'] }}" class="button primary" target="_blank" rel="noopener noreferrer">{{ $appSettings['community_external_cta_label'] ?: 'Open Community Space' }}</a>
                        <p class="muted">Opens in a new tab.</p>
                    </div>
                </article>
            @endif

            <section class="community-lane is-active" data-community-panel="people">
                <div class="community-people-layout">
                    <article class="card community-start-card">
                        <span class="eyebrow">Start here</span>
                        <h3>Introduce yourself</h3>
                        <p>Let people know what brought you here, what you are building, and who you would love to meet before the conference gets busy.</p>
                        <a href="{{ $selectedTopic ? '/community/topics/'.$selectedTopic->slug : '/community' }}" class="button">Open introductions</a>
                    </article>

                    <div class="community-people-grid">
                        @forelse ($recentPosts->take(6) as $post)
                            <a href="/community/topics/{{ $post->topic->slug }}#post-{{ $post->id }}" class="card community-person-tile">
                                <div class="community-person-tile__top">
                                    @if ($post->user->profile_photo_path)
                                        <img src="{{ asset('storage/'.$post->user->profile_photo_path) }}" alt="{{ $post->user->name }} profile photo" class="community-person-tile__avatar">
                                    @else
                                        <div class="community-person-tile__avatar community-person-tile__avatar--fallback">
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="community-person-tile__time">{{ $post->created_at?->diffForHumans() }}</span>
                                </div>
                                <div class="community-person-tile__body">
                                    <h4>{{ $post->user->name }}</h4>
                                    <p>{{ $post->introField('headline') ?: \Illuminate\Support\Str::limit(trim(($post->user->title ? $post->user->title.' · ' : '').($post->user->organization ?? ''), ' ·'), 78) ?: 'Tap to learn more.' }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="card">
                                <p class="muted" style="margin:0;">Introductions will show up here once attendees start posting.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="community-lane" data-community-panel="topics" hidden>
                <div class="community-topic-grid">
                    @foreach ($topics as $topic)
                        <article class="card community-topic-tile {{ $topic->is_intro ? 'community-topic-tile--featured card-loud' : '' }}">
                            <div class="community-topic-tile__meta">
                                <span>{{ $topic->published_posts_count }} {{ \Illuminate\Support\Str::plural('post', $topic->published_posts_count) }}</span>
                                @if($topic->is_intro)
                                    <span>Start here</span>
                                @endif
                            </div>
                            <h4><a href="/community/topics/{{ $topic->slug }}">{{ $topic->title }}</a></h4>
                            <p>{{ $topic->description ?: $topic->prompt }}</p>
                            <a href="/community/topics/{{ $topic->slug }}" class="button secondary">{{ $topic->is_intro ? 'Introduce Yourself' : 'Open Topic' }}</a>
                        </article>
                    @endforeach
                </div>
            </section>
        </section>

        <section class="panel stack">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Badges</span>
                    <h3 style="margin: 12px 0 0; font-size:1.2rem;">Ways to participate</h3>
                </div>
            </div>

            <div class="grid grid-3 badge-gallery">
                @foreach ($badges as $badge)
                    @php($earned = auth()->user()->earnedBadges->firstWhere('badge_id', $badge->id))
                    <article class="card badge-tile {{ $earned ? 'badge-tile-earned card-loud' : 'badge-tile-locked' }}">
                        @include('partials.badges.art', ['badge' => $badge, 'earned' => (bool) $earned, 'size' => 'full'])
                        <p class="muted badge-state">{{ $earned ? 'Earned' : 'Locked' }}</p>
                        <h4 style="margin:0 0 8px;">{{ $badge->name }}</h4>
                        <p class="muted" style="margin:0;">{{ $badge->description }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="panel stack community-leaderboard-shell">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Leaderboard</span>
                    <h3 style="margin: 12px 0 0; font-size:1.2rem;">Community momentum</h3>
                </div>
                <p class="muted" style="margin:0;">Who is earning badges and building the most energy in the community.</p>
            </div>

            <div class="community-leaderboard-list">
                @forelse ($leaderboardUsers as $leader)
                    <article class="card community-leaderboard-row {{ $leader->id === auth()->id() ? 'community-leaderboard-row--current card-loud' : '' }}">
                        <div class="community-leaderboard-row__rank">
                            <span>#{{ $loop->iteration }}</span>
                        </div>

                        <div class="community-leaderboard-row__person">
                            @if ($leader->profile_photo_path)
                                <img src="{{ asset('storage/'.$leader->profile_photo_path) }}" alt="{{ $leader->name }} profile photo" class="community-leaderboard-row__avatar">
                            @else
                                <div class="community-leaderboard-row__avatar community-leaderboard-row__avatar--fallback">
                                    {{ strtoupper(substr($leader->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="community-leaderboard-row__copy">
                                <div class="community-leaderboard-row__identity">
                                    <h4>{{ $leader->name }}</h4>
                                    @if ($leader->id === auth()->id())
                                        <span class="meta-pill">You</span>
                                    @endif
                                </div>
                                <p>{{ trim(($leader->title ? $leader->title.' · ' : '').($leader->organization ?? ''), ' ·') ?: $appSettings['brand_name'].' attendee' }}</p>

                                <div class="community-leaderboard-row__badges">
                                    @forelse ($leader->earnedBadges->take(4) as $earnedBadge)
                                        @include('partials.badges.art', ['badge' => $earnedBadge->badge, 'earned' => true, 'size' => 'mini'])
                                    @empty
                                        <span class="muted">No badges yet</span>
                                    @endforelse
                                    @if ($leader->earnedBadges->count() > 4)
                                        <span class="community-leaderboard-row__more">+{{ $leader->earnedBadges->count() - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="community-leaderboard-row__stats">
                            <div class="community-leaderboard-row__stat">
                                <span>Points</span>
                                <strong>{{ (int) $leader->leaderboard_points }}</strong>
                            </div>
                            <div class="community-leaderboard-row__stat">
                                <span>Badges</span>
                                <strong>{{ (int) $leader->leaderboard_badge_count }}</strong>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="card">
                        <p class="muted" style="margin:0;">Leaderboard activity will show up once attendees start earning points and badges.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    @push('page-styles')
        <style>
            .community-hub,
            .community-hub__header,
            .community-hub__intro,
            .community-partner-card,
            .community-people-layout,
            .community-people-grid,
            .community-topic-grid {
                display: grid;
            }

            .community-hub {
                gap: 18px;
            }

            .community-hub__header {
                grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.9fr);
                gap: 14px;
                align-items: start;
            }

            .community-hub__intro {
                gap: 10px;
            }

            .community-hub__title,
            .community-hub__subtitle {
                margin-bottom: 0;
            }

            .community-hub__score {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .community-score-card {
                display: grid;
                gap: 4px;
                padding: 14px;
            }

            .community-score-card strong {
                font-size: 1.35rem;
                line-height: 1.08;
                letter-spacing: -0.04em;
            }

            .community-score-card--wide {
                grid-column: 1 / -1;
            }

            .community-lane-toggle {
                display: inline-flex;
                gap: 6px;
                padding: 5px;
                border-radius: 999px;
                background: var(--stat-bg);
                border: 1px solid var(--brand-line);
                width: fit-content;
                max-width: 100%;
            }

            .community-lane-toggle__button {
                min-height: 40px;
                padding: 9px 14px;
                border-radius: 999px;
                border: 0;
                background: transparent;
                color: var(--brand-muted);
                font-weight: 700;
                cursor: pointer;
            }

            .community-lane-toggle__button.is-active {
                background: var(--button-gradient);
                color: #fff;
                box-shadow: var(--button-shadow);
            }

            .community-lane[hidden] {
                display: none;
            }

            .community-partner-card {
                grid-template-columns: minmax(0, 1.2fr) auto;
                gap: 16px;
                align-items: center;
                padding: 18px;
                border-width: 1.5px;
            }

            .community-partner-card__copy {
                display: grid;
                gap: 8px;
            }

            .community-partner-card__copy h3,
            .community-partner-card__copy p {
                margin: 0;
            }

            .community-partner-card__copy p {
                color: var(--brand-muted);
                line-height: 1.6;
                max-width: 66ch;
            }

            .community-partner-card__actions {
                display: grid;
                gap: 8px;
                justify-items: start;
            }

            .community-partner-card__actions .muted {
                margin: 0;
                font-size: 0.88rem;
            }

            .community-people-layout {
                grid-template-columns: minmax(280px, 0.82fr) minmax(0, 1.18fr);
                gap: 12px;
                align-items: start;
            }

            .community-start-card,
            .community-topic-tile {
                display: grid;
                gap: 10px;
                align-content: start;
            }

            .community-start-card h3,
            .community-topic-tile h4,
            .community-person-tile h4 {
                margin: 0;
                letter-spacing: -0.03em;
            }

            .community-start-card p,
            .community-topic-tile p,
            .community-person-tile p {
                margin: 0;
                color: var(--brand-muted);
                line-height: 1.55;
            }

            .community-people-grid,
            .community-topic-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .community-person-tile {
                text-decoration: none;
                color: inherit;
                display: grid;
                gap: 10px;
            }

            .community-person-tile__top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
            }

            .community-person-tile__avatar {
                width: 58px;
                height: 58px;
                border-radius: 18px;
                object-fit: cover;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
            }

            .community-person-tile__avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.1rem;
                font-weight: 800;
            }

            .community-person-tile__time {
                color: var(--brand-muted);
                font-size: 0.82rem;
                font-weight: 700;
            }

            .community-person-tile__body {
                display: grid;
                gap: 4px;
            }

            .community-topic-tile__meta {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                color: var(--brand-muted);
                font-size: 0.82rem;
                font-weight: 700;
            }

            .community-topic-tile--featured {
                border-width: 1.5px;
                box-shadow: 0 18px 38px rgba(17, 23, 48, 0.1);
            }

            .community-leaderboard-shell,
            .community-leaderboard-list,
            .community-leaderboard-row,
            .community-leaderboard-row__person,
            .community-leaderboard-row__copy,
            .community-leaderboard-row__stats {
                display: grid;
            }

            .community-leaderboard-list {
                gap: 10px;
            }

            .community-leaderboard-row {
                grid-template-columns: auto minmax(0, 1fr) auto;
                gap: 14px;
                align-items: center;
                padding: 16px;
            }

            .community-leaderboard-row--current {
                border-width: 1.5px;
            }

            .community-leaderboard-row__rank {
                width: 46px;
                height: 46px;
                border-radius: 16px;
                display: grid;
                place-items: center;
                background: var(--stat-bg);
                border: 1px solid var(--brand-line);
                font-weight: 800;
                color: var(--brand-ink);
            }

            .community-leaderboard-row__person {
                grid-template-columns: auto minmax(0, 1fr);
                gap: 12px;
                align-items: center;
            }

            .community-leaderboard-row__avatar {
                width: 64px;
                height: 64px;
                border-radius: 20px;
                object-fit: cover;
                box-shadow: 0 10px 22px rgba(17, 23, 48, 0.12);
            }

            .community-leaderboard-row__avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.2rem;
                font-weight: 800;
            }

            .community-leaderboard-row__copy {
                gap: 6px;
                min-width: 0;
            }

            .community-leaderboard-row__identity {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            .community-leaderboard-row__identity h4,
            .community-leaderboard-row__copy p {
                margin: 0;
            }

            .community-leaderboard-row__copy p {
                color: var(--brand-muted);
                line-height: 1.5;
            }

            .community-leaderboard-row__badges {
                display: flex;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
            }

            .community-leaderboard-row__badges .badge-art--mini {
                width: 32px;
                height: 32px;
            }

            .community-leaderboard-row__more {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                background: var(--stat-bg);
                border: 1px solid var(--brand-line);
                color: var(--brand-muted);
                font-size: 0.8rem;
                font-weight: 700;
            }

            .community-leaderboard-row__stats {
                grid-template-columns: repeat(2, minmax(72px, auto));
                gap: 10px;
                justify-content: end;
            }

            .community-leaderboard-row__stat {
                display: grid;
                gap: 4px;
                padding: 10px 12px;
                border-radius: 16px;
                background: var(--stat-bg);
                border: 1px solid var(--brand-line);
                text-align: center;
            }

            .community-leaderboard-row__stat span {
                color: var(--brand-muted);
                font-size: 0.76rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .community-leaderboard-row__stat strong {
                font-size: 1.1rem;
                line-height: 1.05;
                letter-spacing: -0.04em;
            }

            .badge-gallery .card {
                backdrop-filter: blur(10px);
            }

            .badge-art {
                position: relative;
                display: inline-grid;
                place-items: center;
            }

            .badge-art img {
                display: block;
                width: 100%;
                height: auto;
            }

            .badge-art--full {
                width: 72px;
                height: 72px;
                margin: 0 auto 8px;
            }

            .badge-art.is-earned {
                transform: translateY(-1px) scale(1.04);
                filter: drop-shadow(0 14px 22px rgba(31, 41, 55, 0.14));
            }

            .badge-art.is-locked {
                opacity: 0.24;
                filter: grayscale(0.18) saturate(0.72);
            }

            @media (max-width: 980px) {
                .community-hub__header,
                .community-partner-card,
                .community-people-layout {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 760px) {
                .community-hub__score,
                .community-people-grid,
                .community-topic-grid {
                    grid-template-columns: 1fr;
                }

                .community-leaderboard-row {
                    grid-template-columns: 1fr;
                    align-items: start;
                }

                .community-leaderboard-row__stats {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    justify-content: stretch;
                }

                .community-lane-toggle {
                    width: 100%;
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
        </style>
    @endpush

    @push('page-scripts')
        <script>
            (() => {
                const buttons = Array.from(document.querySelectorAll('[data-community-lane]'));
                const panels = Array.from(document.querySelectorAll('[data-community-panel]'));

                if (!buttons.length || !panels.length) {
                    return;
                }

                const activateLane = (lane) => {
                    buttons.forEach((button) => {
                        const active = button.dataset.communityLane === lane;
                        button.classList.toggle('is-active', active);
                        button.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    panels.forEach((panel) => {
                        panel.hidden = panel.dataset.communityPanel !== lane;
                    });
                };

                buttons.forEach((button) => {
                    button.addEventListener('click', () => activateLane(button.dataset.communityLane));
                });
            })();
        </script>
    @endpush
@endsection
