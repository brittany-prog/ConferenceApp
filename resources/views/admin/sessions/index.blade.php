@extends('layouts.app')

@section('title', 'Sessions | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div class="admin-header">
            <div>
                <span class="eyebrow">Agenda</span>
                <h2 style="margin: 10px 0 0;">Admin sessions</h2>
                <p class="lede" style="margin:0;">Manage session titles, timing, speakers, and room details in one place.</p>
            </div>
            <div class="admin-header-actions">
                <a href="/admin/sessions/export/feedback" class="button secondary">Download Feedback CSV</a>
                <a href="/admin/sessions/create" class="button">Add Session</a>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Day</th>
                        <th>Track</th>
                        <th>Agenda Topic</th>
                        <th>Speakers</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Feedback</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>{{ $session->title }}</td>
                            <td>{{ $session->day->name ?? '—' }}</td>
                            <td>{{ $session->track->name ?? '—' }}</td>
                            <td>{{ \App\Models\Session::agendaTrackForSlug($session->agenda_track_type)['name'] ?? 'Auto-detect' }}</td>
                            <td>{{ $session->resolvedSpeakers()->pluck('name')->implode(', ') ?: '—' }}</td>
                            <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                            <td>{{ $session->location }}</td>
                            <td>{{ $session->feedback_count }}</td>
                            <td>
                                <div class="admin-row-actions">
                                    @if ($session->feedback_count > 0)
                                        <a href="/admin/sessions/{{ $session->id }}/export-feedback" class="button secondary admin-row-action-button">Export Feedback</a>
                                    @endif
                                    <a href="/admin/sessions/{{ $session->id }}/edit" class="button secondary admin-row-action-button">Edit</a>
                                    <form method="POST" action="/admin/sessions/{{ $session->id }}" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button secondary admin-row-action-button" onclick="return confirm('Delete this session?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="muted">No sessions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @push('page-styles')
        <style>
            .admin-header-actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

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
