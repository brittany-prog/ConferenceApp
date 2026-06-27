@extends('layouts.app')

@section('title', 'Announcements | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div class="admin-header">
            <div>
                <span class="eyebrow">Announcements</span>
                <h2 style="margin: 10px 0 0;">Admin announcements</h2>
                <p class="lede" style="margin:0;">Manage in-app updates and choose whether each message stays internal, sends as a test, or emails all active users.</p>
            </div>
            <a href="/admin/announcements/create" class="button">Add announcement</a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->title }}</td>
                            <td>{{ $announcement->message }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    <a href="/admin/announcements/{{ $announcement->id }}/edit" class="button secondary admin-row-action-button">Edit</a>
                                    <form method="POST" action="/admin/announcements/{{ $announcement->id }}" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button secondary admin-row-action-button" onclick="return confirm('Delete this announcement?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">No announcements yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @push('page-styles')
        <style>
            .admin-row-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .admin-row-action-button {
                min-height: 42px;
                justify-content: center;
            }
        </style>
    @endpush
@endsection
