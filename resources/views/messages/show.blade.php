@extends('layouts.app')

@section('title', 'Chat | '.$recipient->name)

@section('content')
    <div class="dark-page-shell">
        <section class="panel card-loud message-thread-page">
            <div class="message-thread-page__header">
                <div class="message-thread-page__identity">
                    @if ($recipient->profile_photo_path)
                        <img src="{{ asset('storage/'.$recipient->profile_photo_path) }}" alt="{{ $recipient->name }} profile photo" class="message-thread-avatar">
                    @else
                        <div class="message-thread-avatar message-thread-avatar--fallback">
                            {{ strtoupper(substr($recipient->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="message-thread-page__identity-copy">
                        <span class="eyebrow">Conversation</span>
                        <h2>{{ $recipient->name }}</h2>
                        <p>{{ trim(($recipient->title ? $recipient->title.' · ' : '').($recipient->organization ?? ''), ' ·') ?: $appSettings['brand_name'].' attendee' }}</p>
                    </div>
                </div>

                <div class="message-thread-page__actions">
                    <a href="/attendees/{{ $recipient->id }}" class="button secondary">View Profile</a>
                    <a href="/messages" class="button">Back to Inbox</a>
                </div>
            </div>

            <div class="message-thread-shell">
                <section class="message-thread-stream">
                    @forelse ($messages as $message)
                        <div class="message-thread-row {{ $message->sender_id === auth()->id() ? 'is-mine' : '' }}">
                            <div class="card message-thread-bubble">
                                <p class="message-thread-bubble__author">{{ $message->sender_id === auth()->id() ? 'You' : $recipient->name }}</p>
                                <p class="message-thread-bubble__body">{{ $message->body }}</p>
                                <p class="message-thread-bubble__time">{{ $message->created_at?->format('M j, g:i A') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <p class="muted" style="margin:0;">No messages yet. Start the conversation below.</p>
                        </div>
                    @endforelse
                </section>

                <aside class="message-thread-side">
                    <div class="card message-thread-side__card">
                        <p class="muted" style="margin:0 0 8px;">Conversation tip</p>
                        <strong>Keep it short and useful.</strong>
                        <p style="margin:8px 0 0;">Good messages usually reference a session, a shared interest, or a simple reason to connect during the event.</p>
                    </div>

                    <form method="POST" action="/messages/{{ $recipient->id }}" class="card message-compose-card">
                        @csrf
                        <div>
                            <label for="body">New message</label>
                            <textarea id="body" name="body" rows="6" placeholder="Say hello, ask a question, or suggest meeting up after a session." required>{{ old('body') }}</textarea>
                        </div>
                        <button type="submit" class="button">Send message</button>
                    </form>
                </aside>
            </div>
        </section>
    </div>

    @push('page-styles')
        <style>
            .message-thread-page,
            .message-thread-page__identity,
            .message-thread-page__identity-copy,
            .message-thread-stream,
            .message-thread-side,
            .message-compose-card {
                display: grid;
            }

            .message-thread-page {
                gap: 20px;
                max-width: 1080px;
                margin: 0 auto;
            }

            .message-thread-page__header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 18px;
                flex-wrap: wrap;
            }

            .message-thread-page__identity {
                align-items: center;
                gap: 16px;
            }

            .message-thread-page__identity-copy {
                gap: 6px;
            }

            .message-thread-page__identity-copy h2,
            .message-thread-page__identity-copy p {
                margin: 0;
            }

            .message-thread-page__identity-copy p {
                color: var(--brand-muted);
            }

            .message-thread-page__actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                align-items: center;
            }

            .message-thread-avatar {
                width: 76px;
                height: 76px;
                object-fit: cover;
                border-radius: 22px;
                box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
            }

            .message-thread-avatar--fallback {
                display: grid;
                place-items: center;
                background: var(--button-gradient);
                color: #fff;
                font-size: 1.4rem;
                font-weight: 800;
            }

            .message-thread-shell {
                display: grid;
                grid-template-columns: minmax(0, 1.25fr) minmax(300px, 0.75fr);
                gap: 18px;
                align-items: start;
            }

            .message-thread-stream,
            .message-thread-side {
                gap: 12px;
            }

            .message-thread-row {
                display: flex;
                justify-content: flex-start;
            }

            .message-thread-row.is-mine {
                justify-content: flex-end;
            }

            .message-thread-bubble {
                max-width: 78%;
                border-radius: 24px;
                background: #fff;
            }

            html[data-theme="dark"] .message-thread-row:not(.is-mine) .message-thread-bubble {
                background: rgba(255, 248, 237, 0.06);
            }

            .message-thread-row.is-mine .message-thread-bubble {
                background: rgba(255, 77, 109, 0.12);
            }

            .message-thread-bubble__author,
            .message-thread-bubble__body,
            .message-thread-bubble__time {
                margin: 0;
            }

            .message-thread-bubble__author {
                color: var(--brand-muted);
                font-size: 0.82rem;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .message-thread-bubble__body {
                line-height: 1.6;
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .message-thread-bubble__time {
                margin-top: 8px;
                color: var(--brand-muted);
                font-size: 0.84rem;
            }

            .message-thread-side__card,
            .message-compose-card {
                gap: 12px;
            }

            .message-thread-side__card p {
                color: var(--brand-muted);
                line-height: 1.55;
            }

            @media (max-width: 900px) {
                .message-thread-shell {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 720px) {
                .message-thread-page__actions {
                    width: 100%;
                    display: grid;
                    grid-template-columns: 1fr;
                }

                .message-thread-page__actions .button {
                    width: 100%;
                    justify-content: center;
                }

                .message-thread-bubble {
                    max-width: 92%;
                }
            }
        </style>
    @endpush
@endsection
