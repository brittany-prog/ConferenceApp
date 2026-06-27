<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Session;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with(['day', 'track', 'speaker', 'speakers'])
            ->withCount('feedback')
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        $days = Day::orderBy('sort_order')->get();
        $tracks = Track::orderBy('name')->get();
        $agendaTrackOptions = Session::agendaTrackOptions();
        $speakers = User::query()->where('is_speaker', true)->orderBy('name')->get();
        $session = new Session();

        return view('admin.sessions.create', compact('days', 'tracks', 'speakers', 'session', 'agendaTrackOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'day_id' => ['required', 'exists:days,id'],
            'track_id' => ['nullable', 'exists:tracks,id'],
            'speaker_user_ids' => ['nullable', 'array'],
            'speaker_user_ids.*' => ['integer', 'exists:users,id'],
            'session_type' => ['nullable', 'string', 'max:255'],
            'agenda_track_type' => ['nullable', 'string', 'in:'.implode(',', Session::agendaTrackSlugs())],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['speaker_id'] = null;
        $speakerIds = collect($request->input('speaker_user_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();
        $validated['speaker_user_id'] = $speakerIds->first();

        $session = Session::create($validated);
        $session->speakers()->sync($speakerIds->all());

        return redirect('/admin/sessions')->with('success', 'Session created successfully.');
    }

    public function edit(Session $session)
    {
        $days = Day::orderBy('sort_order')->get();
        $tracks = Track::orderBy('name')->get();
        $agendaTrackOptions = Session::agendaTrackOptions();
        $speakers = User::query()->where('is_speaker', true)->orderBy('name')->get();
        $session->load('speakers');

        return view('admin.sessions.edit', compact('session', 'days', 'tracks', 'speakers', 'agendaTrackOptions'));
    }

    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'day_id' => ['required', 'exists:days,id'],
            'track_id' => ['nullable', 'exists:tracks,id'],
            'speaker_user_ids' => ['nullable', 'array'],
            'speaker_user_ids.*' => ['integer', 'exists:users,id'],
            'session_type' => ['nullable', 'string', 'max:255'],
            'agenda_track_type' => ['nullable', 'string', 'in:'.implode(',', Session::agendaTrackSlugs())],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['speaker_id'] = null;
        $speakerIds = collect($request->input('speaker_user_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();
        $validated['speaker_user_id'] = $speakerIds->first();

        $session->update($validated);
        $session->speakers()->sync($speakerIds->all());

        return redirect('/admin/sessions')->with('success', 'Session updated successfully.');
    }

    public function destroy(Session $session)
    {
        $session->delete();

        return redirect('/admin/sessions')->with('success', 'Session deleted successfully.');
    }

    public function exportFeedback()
    {
        $sessions = Session::query()
            ->with(['day', 'track', 'feedback.user'])
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        return response()->streamDownload(function () use ($sessions) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Session Title',
                'Day',
                'Track',
                'Start Time',
                'End Time',
                'Location',
                'Attendee Name',
                'Attendee Email',
                'Rating',
                'Comment',
                'Submitted At',
            ]);

            foreach ($sessions as $session) {
                foreach ($session->feedback as $feedback) {
                    fputcsv($handle, [
                        $session->title,
                        $session->day->name ?? '',
                        $session->track->name ?? '',
                        $session->start_time,
                        $session->end_time,
                        $session->location,
                        $feedback->user->name ?? '',
                        $feedback->user->email ?? '',
                        $feedback->rating,
                        $feedback->comment,
                        $feedback->created_at?->toDateTimeString(),
                    ]);
                }
            }

            fclose($handle);
        }, 'southern-spark-session-feedback.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportSessionFeedback(Session $session)
    {
        $session->load(['day', 'track', 'feedback.user']);
        $filename = 'session-feedback-'.str($session->title)->slug()->value().'.csv';

        return response()->streamDownload(function () use ($session) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Session Title',
                'Day',
                'Track',
                'Start Time',
                'End Time',
                'Location',
                'Attendee Name',
                'Attendee Email',
                'Rating',
                'Comment',
                'Submitted At',
            ]);

            foreach ($session->feedback as $feedback) {
                fputcsv($handle, [
                    $session->title,
                    $session->day->name ?? '',
                    $session->track->name ?? '',
                    $session->start_time,
                    $session->end_time,
                    $session->location,
                    $feedback->user->name ?? '',
                    $feedback->user->email ?? '',
                    $feedback->rating,
                    $feedback->comment,
                    $feedback->created_at?->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
