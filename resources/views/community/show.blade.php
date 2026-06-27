@extends('layouts.app')

@section('title', $topic->title.' | Community')

@section('content')
    <div class="dark-page-shell">
        <section class="panel card-loud community-topic-page">
            <div class="community-topic-page__header">
                <div class="community-topic-page__intro">
                    <span class="eyebrow">Community</span>
                    <h2>{{ $topic->title }}</h2>
                    @if ($topic->description)
                        <p>{{ $topic->description }}</p>
                    @endif
                </div>

                <div class="community-topic-page__summary">
                    <article class="card community-topic-summary-card">
                        <span class="muted">Points</span>
                        <strong>{{ $sparkSummary['points'] }}</strong>
                    </article>
                    <article class="card community-topic-summary-card">
                        <span class="muted">Badges</span>
                        <strong>{{ $sparkSummary['badge_count'] }}</strong>
                    </article>
                    <a href="/community" class="button secondary">All topics</a>
                </div>
            </div>

            <nav class="section-tabs community-topic-switcher" aria-label="Community topics">
                @foreach ($topics as $navTopic)
                    <a href="/community/topics/{{ $navTopic->slug }}" @class(['section-tab', 'is-current' => $navTopic->id === $topic->id])>
                        {{ $navTopic->title }}
                    </a>
                @endforeach
            </nav>

            <div class="community-topic-layout">
                <section class="card community-compose-card">
                    @if ($topic->prompt)
                        <div class="surface-soft">
                            <p class="muted" style="margin:0 0 8px;">Discussion prompt</p>
                            <p style="margin:0;">{{ $topic->prompt }}</p>
                        </div>
                    @endif

                    <form method="POST" action="/community/topics/{{ $topic->slug }}/posts" class="stack community-compose-form">
                        @csrf
                        @if ($topic->is_intro)
                            <div class="surface-soft">
                                <p class="muted" style="margin:0 0 6px;">Keep it brief</p>
                                <p style="margin:0;">A short, warm introduction is enough. The goal is to help people understand who you are and what conversations you want to have.</p>
                            </div>

                            <div class="grid grid-2 community-intro-grid">
                                <div>
                                    <label for="headline">Short headline</label>
                                    <input id="headline" type="text" name="headline" value="{{ $introFields['headline'] }}" placeholder="AI educator exploring classroom implementation">
                                </div>
                                <div>
                                    <label for="role_title">Role / title</label>
                                    <input id="role_title" type="text" name="role_title" value="{{ $introFields['role_title'] }}" placeholder="Instructional coach">
                                </div>
                            </div>

                            <div>
                                <label for="organization">Organization</label>
                                <input id="organization" type="text" name="organization" value="{{ $introFields['organization'] }}" placeholder="Jackson Public Schools">
                            </div>

                            <div>
                                <label for="why_here">What brought you to this event?</label>
                                <textarea id="why_here" name="why_here" rows="3" placeholder="I am here to learn how others are using AI in practical, people-centered ways.">{{ $introFields['why_here'] }}</textarea>
                            </div>
                            <div>
                                <label for="building">What are you building, learning, or exploring?</label>
                                <textarea id="building" name="building" rows="3" placeholder="I am exploring how AI can support teacher planning and student literacy.">{{ $introFields['building'] }}</textarea>
                            </div>
                            <div>
                                <label for="meet">Who would you love to meet here?</label>
                                <textarea id="meet" name="meet" rows="3" placeholder="I would love to connect with educators, district leaders, and founders building practical classroom tools.">{{ $introFields['meet'] }}</textarea>
                            </div>
                            <div>
                                <label for="body">Anything else people should know?</label>
                                <textarea id="body" name="body" rows="4" placeholder="Add a warm closing note or anything else you want people to know about you.">{{ old('body', $userIntroduction->body ?? '') }}</textarea>
                            </div>
                        @else
                            <div>
                                <label for="body">Add your perspective</label>
                                <textarea id="body" name="body" rows="5" placeholder="Add a thoughtful response to this discussion topic.">{{ old('body') }}</textarea>
                            </div>
                        @endif

                        <div class="action-row">
                            <p class="subtle" style="margin:0;">{{ $topic->is_intro ? 'You can update your introduction any time.' : 'Thoughtful replies help you earn badges and build connections.' }}</p>
                            <button type="submit" class="button">{{ $topic->is_intro ? ($userIntroduction ? 'Update introduction' : 'Post introduction') : 'Share response' }}</button>
                        </div>
                    </form>
                </section>

                <section class="community-feed">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">Conversation</span>
                            <h3 style="margin: 12px 0 0; font-size:1.2rem;">Responses</h3>
                        </div>
                    </div>

                    @forelse ($topic->posts as $post)
                        @if ($topic->is_intro)
                            <article id="post-{{ $post->id }}" class="card community-intro-card">
                                <details class="community-intro-card__details">
                                    <summary class="community-intro-card__summary">
                                        <div class="community-intro-card__identity">
                                            @if ($post->user->profile_photo_path)
                                                <img src="{{ asset('storage/'.$post->user->profile_photo_path) }}" alt="{{ $post->user->name }} profile photo" class="community-author-avatar community-intro-card__avatar">
                                            @else
                                                <div class="community-author-avatar community-author-avatar--fallback community-intro-card__avatar">
                                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="community-intro-card__copy">
                                                <h4>{{ $post->user->name }}</h4>
                                                <p>{{ $post->introField('headline') ?: trim(($post->user->title ? $post->user->title.' · ' : '').($post->user->organization ?? ''), ' ·') ?: 'Tap to learn more.' }}</p>
                                            </div>
                                        </div>
                                        <div class="community-intro-card__summary-side">
                                            <span class="community-intro-card__time">{{ $post->created_at?->diffForHumans() }}</span>
                                            <span class="community-intro-card__chevron" aria-hidden="true">+</span>
                                        </div>
                                    </summary>

                                    <div class="community-intro-card__body">
                                        <div class="community-intro-card__facts">
                                            @if ($post->user->title)
                                                <span class="community-intro-card__chip">{{ $post->user->title }}</span>
                                            @endif
                                            @if ($post->user->organization)
                                                <span class="community-intro-card__chip">{{ $post->user->organization }}</span>
                                            @endif
                                        </div>

                                        @if ($post->introField('why_here'))
                                            <div class="surface-soft">
                                                <p class="muted" style="margin:0 0 6px;">What brought me here</p>
                                                <p style="margin:0;">{{ $post->introField('why_here') }}</p>
                                            </div>
                                        @endif

                                        @if ($post->introField('building'))
                                            <div class="surface-soft">
                                                <p class="muted" style="margin:0 0 6px;">What I’m building or learning</p>
                                                <p style="margin:0;">{{ $post->introField('building') }}</p>
                                            </div>
                                        @endif

                                        @if ($post->introField('meet'))
                                            <div class="surface-soft">
                                                <p class="muted" style="margin:0 0 6px;">Who I’d love to meet</p>
                                                <p style="margin:0;">{{ $post->introField('meet') }}</p>
                                            </div>
                                        @endif

                                        @if ($post->body)
                                            <p class="community-intro-card__note">{!! nl2br(e($post->body)) !!}</p>
                                        @endif

                                        <div class="community-intro-card__actions">
                                            <a href="/attendees/{{ $post->user->id }}">View profile</a>
                                        </div>

                                        <form method="POST" action="/community/topics/{{ $topic->slug }}/posts" class="stack community-reply-form">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $post->id }}">
                                            <label for="reply-{{ $post->id }}">Reply</label>
                                            <textarea id="reply-{{ $post->id }}" name="body" rows="3" placeholder="Add a helpful reply to keep the conversation moving."></textarea>
                                            <div class="action-row">
                                                <p class="subtle" style="margin:0;">Replies earn points, with a small daily cap to keep things thoughtful.</p>
                                                <button type="submit" class="button secondary">Reply</button>
                                            </div>
                                        </form>

                                        @if ($post->replies->isNotEmpty())
                                            <div class="community-replies">
                                                @foreach ($post->replies as $reply)
                                                    <div id="post-{{ $reply->id }}" class="card community-reply-card">
                                                        <div class="community-author community-author--reply">
                                                            @if ($reply->user->profile_photo_path)
                                                                <img src="{{ asset('storage/'.$reply->user->profile_photo_path) }}" alt="{{ $reply->user->name }} profile photo" class="community-author-avatar community-author-avatar--reply">
                                                            @else
                                                                <div class="community-author-avatar community-author-avatar--fallback community-author-avatar--reply">
                                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="muted" style="margin:0 0 4px;">{{ $reply->user->name }}</p>
                                                                <p class="muted" style="margin:0;">{{ $reply->created_at?->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                        <p style="margin:0;">{!! nl2br(e($reply->body)) !!}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </details>
                            </article>
                        @else
                            <article id="post-{{ $post->id }}" class="card community-post-card">
                                <div class="community-post-card__header">
                                    <div class="community-author">
                                        @if ($post->user->profile_photo_path)
                                            <img src="{{ asset('storage/'.$post->user->profile_photo_path) }}" alt="{{ $post->user->name }} profile photo" class="community-author-avatar">
                                        @else
                                            <div class="community-author-avatar community-author-avatar--fallback">
                                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="muted" style="margin:0 0 6px;">{{ $post->created_at?->diffForHumans() }} @if($post->is_pinned) · Pinned by organizers @endif</p>
                                            <h4 style="margin:0;">{{ $post->user->name }}</h4>
                                        </div>
                                    </div>
                                    <a href="/attendees/{{ $post->user->id }}">View profile</a>
                                </div>

                                <p style="margin:0;">{!! nl2br(e($post->body)) !!}</p>

                                <form method="POST" action="/community/topics/{{ $topic->slug }}/posts" class="stack community-reply-form">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $post->id }}">
                                    <label for="reply-{{ $post->id }}">Reply</label>
                                    <textarea id="reply-{{ $post->id }}" name="body" rows="3" placeholder="Add a helpful reply to keep the conversation moving."></textarea>
                                    <div class="action-row">
                                        <p class="subtle" style="margin:0;">Replies earn points, with a small daily cap to keep things thoughtful.</p>
                                        <button type="submit" class="button secondary">Reply</button>
                                    </div>
                                </form>

                                @if ($post->replies->isNotEmpty())
                                    <div class="community-replies">
                                        @foreach ($post->replies as $reply)
                                            <div id="post-{{ $reply->id }}" class="card community-reply-card">
                                                <div class="community-author community-author--reply">
                                                    @if ($reply->user->profile_photo_path)
                                                        <img src="{{ asset('storage/'.$reply->user->profile_photo_path) }}" alt="{{ $reply->user->name }} profile photo" class="community-author-avatar community-author-avatar--reply">
                                                    @else
                                                        <div class="community-author-avatar community-author-avatar--fallback community-author-avatar--reply">
                                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="muted" style="margin:0 0 4px;">{{ $reply->user->name }}</p>
                                                        <p class="muted" style="margin:0;">{{ $reply->created_at?->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                <p style="margin:0;">{!! nl2br(e($reply->body)) !!}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </article>
                        @endif
                    @empty
                        <div class="card">
                            <p class="muted" style="margin:0;">No one has responded yet. Be the first to get this conversation started.</p>
                        </div>
                    @endforelse
                </section>
            </div>
        </section>
    </div>

    @push('page-styles')
        <style>
            .community-topic-page,
            .community-topic-page__header,
            .community-topic-page__intro,
            .community-feed,
            .community-replies {
                display: grid;
            }

            .community-topic-page {
                gap: 20px;
            }

            .community-topic-page__header {
                grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.85fr);
                gap: 18px;
                align-items: start;
            }

            .community-topic-page__intro {
                gap: 10px;
            }

            .community-topic-page__intro h2,
            .community-topic-page__intro p {
                margin: 0;
            }

            .community-topic-page__intro p {
                color: var(--brand-muted);
                max-width: 720px;
                line-height: 1.6;
            }

            .community-topic-page__summary {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .community-topic-summary-card {
                display: grid;
                gap: 6px;
                padding: 16px;
            }

            .community-topic-summary-card strong {
                font-size: 1.35rem;
                line-height: 1.08;
                letter-spacing: -0.04em;
            }

            .community-topic-switcher {
                width: 100%;
            }

            .community-topic-layout {
                display: grid;
                grid-template-columns: minmax(320px, 0.88fr) minmax(0, 1.12fr);
                gap: 18px;
                align-items: start;
            }

            .community-compose-card,
            .community-feed {
                gap: 14px;
            }

            .community-intro-grid {
                gap: 14px;
            }

            .community-author {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                min-width: 0;
            }

            .community-author--reply {
                margin-bottom: 8px;
            }

            .community-author-avatar {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                object-fit: cover;
                flex: 0 0 auto;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
            }

            .community-author-avatar--reply {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .community-author-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1rem;
                font-weight: 800;
            }

            .community-intro-card,
            .community-post-card {
                gap: 14px;
            }

            .community-post-card__header {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .community-intro-card__details {
                border: 1px solid var(--brand-line);
                border-radius: 24px;
                background: var(--card-loud-bg);
                overflow: hidden;
                box-shadow: 0 10px 24px rgba(17, 23, 48, 0.08);
            }

            .community-intro-card__summary {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                align-items: center;
                padding: 16px 18px;
                cursor: pointer;
                list-style: none;
            }

            .community-intro-card__summary::-webkit-details-marker {
                display: none;
            }

            .community-intro-card__identity {
                display: flex;
                align-items: center;
                gap: 14px;
                min-width: 0;
                flex: 1 1 auto;
            }

            .community-intro-card__avatar {
                width: 58px;
                height: 58px;
                border-radius: 18px;
            }

            .community-intro-card__copy {
                display: grid;
                gap: 4px;
                min-width: 0;
            }

            .community-intro-card__copy h4,
            .community-intro-card__copy p {
                margin: 0;
            }

            .community-intro-card__copy p {
                color: var(--brand-muted);
            }

            .community-intro-card__summary-side {
                display: grid;
                justify-items: end;
                gap: 10px;
                flex: 0 0 auto;
            }

            .community-intro-card__time {
                color: var(--brand-muted);
                font-size: 0.84rem;
                font-weight: 700;
            }

            .community-intro-card__chevron {
                display: inline-grid;
                place-items: center;
                width: 34px;
                height: 34px;
                border-radius: 999px;
                background: var(--stat-bg);
                color: var(--brand-ink);
                font-size: 1.25rem;
                line-height: 1;
            }

            .community-intro-card__details[open] .community-intro-card__summary {
                border-bottom: 1px solid var(--brand-line);
            }

            .community-intro-card__details[open] .community-intro-card__chevron {
                transform: rotate(45deg);
            }

            .community-intro-card__body {
                display: grid;
                gap: 12px;
                padding: 16px 18px 18px;
            }

            .community-intro-card__facts {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .community-intro-card__chip {
                display: inline-flex;
                align-items: center;
                min-height: 28px;
                padding: 5px 10px;
                border-radius: 999px;
                background: var(--stat-bg);
                border: 1px solid var(--brand-line);
                color: var(--brand-muted);
                font-size: 0.82rem;
                font-weight: 600;
            }

            .community-intro-card__note {
                margin: 0;
                line-height: 1.55;
            }

            .community-intro-card__actions {
                display: flex;
                justify-content: flex-start;
            }

            .community-replies {
                gap: 10px;
                padding-left: 18px;
                border-left: 1px solid var(--brand-line);
            }

            .community-reply-card {
                background: var(--stat-bg);
            }

            .community-reply-form {
                gap: 10px;
            }

            @media (max-width: 980px) {
                .community-topic-page__header,
                .community-topic-layout {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 720px) {
                .community-topic-page__summary,
                .community-intro-grid {
                    grid-template-columns: 1fr;
                }

                .community-topic-switcher {
                    overflow-x: auto;
                    flex-wrap: nowrap;
                    padding-inline: 4px;
                    scrollbar-width: none;
                }

                .community-topic-switcher::-webkit-scrollbar {
                    display: none;
                }

                .community-topic-switcher .section-tab {
                    flex: 0 0 auto;
                    min-width: max-content;
                }

                .community-post-card__header,
                .community-intro-card__summary {
                    flex-direction: column;
                    align-items: stretch;
                }

                .community-intro-card__summary-side {
                    justify-items: start;
                }

                .community-replies {
                    padding-left: 12px;
                }
            }
        </style>
    @endpush
@endsection
