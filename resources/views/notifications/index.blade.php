@extends('layouts.app')

@section('title', 'Notifications | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 920px; margin: 0 auto;">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Notifications</span>
                <h2 style="margin: 10px 0 0;">Stay in the loop</h2>
                <p class="muted">Messages, announcements, and upcoming saved sessions all in one place.</p>
            </div>
            <a href="/app" class="button secondary">Back to Home</a>
        </div>

        @forelse ($notifications as $notification)
            <article class="card card-loud notification-card">
                <div class="notification-copy">
                    <span class="eyebrow">{{ ucfirst($notification['type']) }}</span>
                    <h3 style="margin:12px 0 8px;">{{ $notification['title'] }}</h3>
                    <p class="muted" style="margin:0;">{{ $notification['body'] }}</p>
                </div>
                <div class="notification-actions">
                    <p class="muted" style="margin:0;">{{ $notification['timestamp']?->diffForHumans() }}</p>
                    <a href="{{ $notification['link'] }}" class="button secondary">Open</a>
                </div>
            </article>
        @empty
            <div class="card">
                <p class="muted" style="margin:0;">You’re all caught up. New messages, announcements, and saved-session reminders will appear here.</p>
            </div>
        @endforelse
    </section>
@endsection

@push('page-styles')
    <style>
        .notification-card {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .notification-copy {
            flex: 1 1 320px;
        }

        .notification-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        @media (max-width: 720px) {
            .notification-actions {
                width: 100%;
                align-items: flex-start;
            }
        }
    </style>
@endpush
