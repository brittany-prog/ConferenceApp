@extends('layouts.app')

@section('title', 'Messages | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell">
        <section class="panel card-loud inbox-page">
            <div class="inbox-page__header">
                <div class="inbox-page__intro">
                    <span class="page-kicker">Inbox</span>
                    <h2 class="page-title inbox-page__title">Messages</h2>
                    <p class="page-subtitle inbox-page__subtitle">Stay connected with other attendees, speakers, and collaborators without losing the thread of the event.</p>
                </div>

                <div class="inbox-page__summary">
                    <article class="card inbox-summary-card">
                        <span class="muted">Conversations</span>
                        <strong>{{ count($conversations) }}</strong>
                    </article>
                    <article class="card inbox-summary-card">
                        <span class="muted">Unread</span>
                        <strong>{{ collect($conversations)->sum('unread_count') }}</strong>
                    </article>
                    <a href="/attendees" class="button secondary">Find Attendees</a>
                </div>
            </div>

            <div class="inbox-list">
                @forelse ($conversations as $conversation)
                    <a href="/messages/{{ $conversation['user']->id }}" class="card inbox-row">
                        <div class="inbox-row__main">
                            @if ($conversation['user']->profile_photo_path)
                                <img src="{{ asset('storage/'.$conversation['user']->profile_photo_path) }}" alt="{{ $conversation['user']->name }} profile photo" class="inbox-row__avatar">
                            @else
                                <div class="inbox-row__avatar inbox-row__avatar--fallback">
                                    {{ strtoupper(substr($conversation['user']->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="inbox-row__copy">
                                <div class="inbox-row__title">
                                    <h3>{{ $conversation['user']->name }}</h3>
                                    <span>{{ $conversation['latest']->created_at?->diffForHumans() }}</span>
                                </div>
                                <p class="inbox-row__role">
                                    {{ trim(($conversation['user']->title ? $conversation['user']->title.' · ' : '').($conversation['user']->organization ?? ''), ' ·') ?: $appSettings['brand_name'].' attendee' }}
                                </p>
                                <p class="inbox-row__preview">{{ \Illuminate\Support\Str::limit($conversation['latest']->body, 115) }}</p>
                            </div>
                        </div>

                        <div class="inbox-row__meta">
                            @if ($conversation['unread_count'] > 0)
                                <span class="inbox-row__badge">{{ $conversation['unread_count'] }} new</span>
                            @endif
                            <span class="inbox-row__cta">Open</span>
                        </div>
                    </a>
                @empty
                    <div class="card">
                        <p class="muted" style="margin:0;">No conversations yet. Visit the attendee directory and send your first message.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    @push('page-styles')
        <style>
            .inbox-page,
            .inbox-page__header,
            .inbox-page__intro,
            .inbox-list {
                display: grid;
            }

            .inbox-page {
                gap: 20px;
            }

            .inbox-page__header {
                grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.85fr);
                gap: 18px;
                align-items: start;
            }

            .inbox-page__intro {
                gap: 12px;
            }

            .inbox-page__title,
            .inbox-page__subtitle {
                margin-bottom: 0;
            }

            .inbox-page__summary {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .inbox-summary-card {
                display: grid;
                gap: 6px;
                padding: 16px;
            }

            .inbox-summary-card strong {
                font-size: 1.35rem;
                line-height: 1.08;
                letter-spacing: -0.04em;
            }

            .inbox-list {
                gap: 12px;
            }

            .inbox-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 18px;
                text-decoration: none;
                color: inherit;
                border-radius: 24px;
                backdrop-filter: blur(10px);
            }

            .inbox-row__main {
                display: flex;
                gap: 14px;
                align-items: flex-start;
                min-width: 0;
                flex: 1 1 auto;
            }

            .inbox-row__avatar {
                width: 64px;
                height: 64px;
                object-fit: cover;
                border-radius: 20px;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
                flex: 0 0 auto;
            }

            .inbox-row__avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.25rem;
                font-weight: 800;
            }

            .inbox-row__copy {
                display: grid;
                gap: 6px;
                min-width: 0;
                flex: 1 1 auto;
            }

            .inbox-row__title {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                align-items: baseline;
            }

            .inbox-row__title h3,
            .inbox-row__role,
            .inbox-row__preview {
                margin: 0;
            }

            .inbox-row__title h3 {
                font-size: 1.06rem;
                letter-spacing: -0.02em;
            }

            .inbox-row__title span,
            .inbox-row__role {
                color: var(--brand-muted);
                font-size: 0.9rem;
            }

            .inbox-row__preview {
                line-height: 1.55;
            }

            .inbox-row__meta {
                display: grid;
                gap: 10px;
                justify-items: end;
                flex: 0 0 auto;
            }

            .inbox-row__badge {
                display: inline-flex;
                align-items: center;
                min-height: 28px;
                padding: 5px 10px;
                border-radius: 999px;
                background: var(--button-gradient);
                color: #fff;
                font-size: 0.82rem;
                font-weight: 800;
            }

            .inbox-row__cta {
                color: var(--brand-muted);
                font-size: 0.9rem;
                font-weight: 700;
            }

            @media (max-width: 920px) {
                .inbox-page__header {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 720px) {
                .inbox-page__summary {
                    grid-template-columns: 1fr;
                }

                .inbox-row,
                .inbox-row__title {
                    flex-direction: column;
                    align-items: stretch;
                }

                .inbox-row__meta {
                    width: 100%;
                    justify-items: start;
                    grid-auto-flow: column;
                    justify-content: space-between;
                    align-items: center;
                }
            }
        </style>
    @endpush
@endsection
