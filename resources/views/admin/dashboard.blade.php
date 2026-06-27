@extends('layouts.app')

@section('title', 'Admin Dashboard | '.$appSettings['brand_name'])

@section('content')
    <section class="panel" style="margin-bottom: 18px;">
        <span class="eyebrow">Admin</span>
        <h2 style="margin: 14px 0 8px;">Dashboard</h2>
        <p class="muted">Manage attendee access, branding, and conference content from one place.</p>
        <div class="admin-dashboard-actions" style="margin-top: 18px;">
            <a href="/admin/users" class="button admin-dashboard-action-button">Users</a>
            <a href="/admin/settings" class="button secondary admin-dashboard-action-button">Branding</a>
            <a href="/admin/community" class="button secondary admin-dashboard-action-button">Community</a>
            <a href="/admin/sponsors" class="button secondary admin-dashboard-action-button">Sponsors</a>
            <a href="/admin/sessions" class="button secondary admin-dashboard-action-button">Sessions</a>
            <a href="/admin/speakers" class="button secondary admin-dashboard-action-button">Speakers</a>
            <a href="/admin/announcements" class="button secondary admin-dashboard-action-button">Announcements</a>
        </div>
    </section>

    <section class="grid grid-3">
        <article class="card">
            <p class="muted">Users</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $usersCount }}</h3>
        </article>
        <article class="card">
            <p class="muted">Admins</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $adminsCount }}</h3>
        </article>
        <article class="card">
            <p class="muted">Disabled logins</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $disabledUsersCount }}</h3>
        </article>
        <article class="card">
            <p class="muted">Sessions</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $sessionsCount }}</h3>
        </article>
        <article class="card">
            <p class="muted">Speakers</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $speakersCount }}</h3>
        </article>
        <article class="card">
            <p class="muted">Announcements</p>
            <h3 style="font-size: 2rem; margin: 0;">{{ $announcementsCount }}</h3>
        </article>
    </section>

    @push('page-styles')
        <style>
            .admin-dashboard-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .admin-dashboard-action-button {
                min-height: 44px;
                justify-content: center;
            }

            @media (max-width: 720px) {
                .admin-dashboard-actions {
                    flex-direction: column;
                }

                .admin-dashboard-action-button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
