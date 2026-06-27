@csrf

@php
    $selectedSpeakerIds = collect(old('speaker_user_ids', $session->resolvedSpeakers()->pluck('id')->all()))
        ->map(fn ($id) => (int) $id)
        ->all();
@endphp

<div class="form-section stack">
    <div>
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $session->title ?? '') }}" required>
    </div>

    <div>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5">{{ old('description', $session->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-2">
        <div>
            <label for="day_id">Day</label>
            <select id="day_id" name="day_id" required>
                <option value="">Select a day</option>
                @foreach($days as $day)
                    <option value="{{ $day->id }}" @selected(old('day_id', $session->day_id ?? '') == $day->id)>{{ $day->name }} ({{ $day->event_date }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="track_id">Track</label>
            <select id="track_id" name="track_id">
                <option value="">Select a track</option>
                @foreach($tracks as $track)
                    <option value="{{ $track->id }}" @selected(old('track_id', $session->track_id ?? '') == $track->id)>{{ $track->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="agenda_track_type">Agenda topic</label>
            <select id="agenda_track_type" name="agenda_track_type">
                <option value="">Auto-detect from session content</option>
                @foreach($agendaTrackOptions as $agendaTrack)
                    <option value="{{ $agendaTrack['slug'] }}" @selected(old('agenda_track_type', $session->agenda_track_type ?? '') === $agendaTrack['slug'])>{{ $agendaTrack['name'] }}</option>
                @endforeach
            </select>
            <p class="muted" style="margin:8px 0 0;">This controls the colored topic label shown in the agenda and public preview.</p>
        </div>
        <div>
            <label for="speaker_user_ids">Speakers</label>
            <select id="speaker_user_ids" name="speaker_user_ids[]" multiple size="6">
                @foreach($speakers as $speaker)
                    <option value="{{ $speaker->id }}" @selected(in_array($speaker->id, $selectedSpeakerIds, true))>{{ $speaker->name }}</option>
                @endforeach
            </select>
            <p class="muted" style="margin:8px 0 0;">Hold Command on Mac or Ctrl on Windows to select co-presenters.</p>
        </div>
        <div>
            <label for="session_type">Session type</label>
            <input id="session_type" type="text" name="session_type" value="{{ old('session_type', $session->session_type ?? '') }}">
        </div>
    </div>

    <div class="grid grid-2">
        <div>
            <label for="start_time">Start time</label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time', isset($session->start_time) ? substr($session->start_time,0,5) : '') }}">
        </div>
        <div>
            <label for="end_time">End time</label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time', isset($session->end_time) ? substr($session->end_time,0,5) : '') }}">
        </div>
    </div>

    <div>
        <label for="location">Location</label>
        <input id="location" type="text" name="location" value="{{ old('location', $session->location ?? '') }}">
    </div>

    <label class="check-row">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $session->is_featured ?? false))>
        <span>Featured session</span>
    </label>
</div>

<div class="action-row">
    <p class="subtle" style="margin:0;">Clear titles, speaker links, timing, and room labels make the agenda much easier to scan on mobile.</p>
    <button type="submit" class="button">Save Session</button>
</div>
