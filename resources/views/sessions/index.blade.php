@extends('layouts.app')

@section('title', 'Sessions | '.$appSettings['brand_name'])

@section('content')
    <div class="dark-page-shell agenda-page-shell">
        @php
            $liveCount = $schedulePulse['currentSessions']->count();
            $isPreviewMode = $isPreviewMode ?? false;
            $sessionPathPrefix = $isPreviewMode ? '/agenda-preview' : '/sessions';
            $agendaBasePath = $isPreviewMode ? '/agenda-preview' : '/sessions';
            $selectedTrack = $selectedTrack ?? null;
            $selectedTracks = $selectedTracks ?? [];
            $searchTerm = $searchTerm ?? '';
            $agendaUrl = function (array $overrides = []) use ($agendaBasePath, $selectedDay, $selectedTrack, $searchTerm) {
                $params = [
                    'day' => $selectedDay,
                    'track' => $selectedTrack,
                    'q' => $searchTerm !== '' ? $searchTerm : null,
                ];

                foreach ($overrides as $key => $value) {
                    if ($value === null || $value === '' || $value === []) {
                        unset($params[$key]);
                        continue;
                    }

                    $params[$key] = $value;
                }

                return $agendaBasePath.(count($params) ? '?'.http_build_query($params) : '');
            };
        @endphp

        <section class="panel stack agenda-shell">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Agenda</span>
                    <h2 style="margin: 10px 0 0;">Conference schedule</h2>
                    <p class="muted">{{ $isPreviewMode ? 'Preview the '.$appSettings['brand_name'].' schedule, explore the sessions, and register when you’re ready to join us.' : 'Move between days quickly, scan what is happening, and save the sessions you actually want in your pocket.' }}</p>
                </div>
                @if ($isPreviewMode)
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        @if (!empty($registrationUrl))
                            <a href="{{ $registrationUrl }}" target="_blank" rel="noreferrer" class="button agenda-toolbar-button">{{ $appSettings['public_ticket_label'] ?: 'Get Tickets' }}</a>
                        @endif
                        <a href="/login" class="button secondary agenda-toolbar-button">Login</a>
                    </div>
                @else
                    <a href="/my-schedule" class="button secondary agenda-toolbar-button">My Schedule</a>
                @endif
            </div>

            @if ($isPreviewMode && ($featuredPreviewSessions ?? collect())->isNotEmpty())
                <section class="agenda-preview-feature-row">
                    @foreach ($featuredPreviewSessions->take(3) as $featuredSession)
                        @php($featuredSpeakers = $featuredSession->resolvedSpeakers())
                        @php($featuredSpeaker = $featuredSpeakers->first())
                        <a href="{{ $sessionPathPrefix }}/{{ $featuredSession->id }}" class="card agenda-preview-feature-card">
                            <div class="agenda-preview-feature-card__top">
                                <span class="eyebrow">Featured</span>
                                <div class="agenda-preview-feature-card__meta">
                                    <span class="meta-pill">{{ $featuredSession->location ?? 'TBD' }}</span>
                                    <span class="meta-pill">{{ $featuredSession->day->name ?? 'Conference Day' }}</span>
                                </div>
                            </div>
                            <div class="agenda-preview-feature-card__body">
                                <h3>{{ $featuredSession->title }}</h3>
                                <p class="muted">{{ \Illuminate\Support\Str::limit($featuredSession->description ?: 'Explore one of the conversations helping define '.$appSettings['brand_name'].' this year.', 140) }}</p>
                            </div>
                            @if ($featuredSpeaker)
                                <div class="agenda-preview-feature-card__speaker">
                                    @if ($featuredSpeaker->profile_photo_path)
                                        <img src="{{ asset('storage/'.$featuredSpeaker->profile_photo_path) }}" alt="{{ $featuredSpeaker->name }} headshot">
                                    @else
                                        <div class="agenda-preview-feature-card__speaker-fallback">{{ strtoupper(substr($featuredSpeaker->name, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <strong>{{ $featuredSpeaker->name }}</strong>
                                        @if ($featuredSpeaker->title || $featuredSpeaker->organization)
                                            <span>{{ collect([$featuredSpeaker->title, $featuredSpeaker->organization])->filter()->implode(' · ') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="agenda-preview-feature-card__footer">
                                <span>{{ $featuredSession->agenda_track['name'] ?? 'General Session' }}</span>
                                <strong>{{ $featuredSession->start_time ?? 'TBD' }}</strong>
                            </div>
                        </a>
                    @endforeach
                </section>
            @endif

            <div class="agenda-discovery-shell">
                @if ($allDays->isNotEmpty())
                    <nav class="section-tabs agenda-day-tabs" aria-label="Agenda day shortcuts">
                        <a href="{{ $agendaUrl(['day' => null]) }}" @class(['section-tab', 'is-current' => ! $selectedDay])>All Days</a>
                        @foreach ($allDays as $day)
                            <a href="{{ $agendaUrl(['day' => $day->id]) }}" @class(['section-tab', 'is-current' => $selectedDay === $day->id])>
                                {{ $day->name }}
                            </a>
                        @endforeach
                    </nav>
                @endif

                <div class="card agenda-control-bar">
                    <form method="GET" action="{{ $agendaBasePath }}" class="agenda-search-form">
                        @if ($selectedDay)
                            <input type="hidden" name="day" value="{{ $selectedDay }}">
                        @endif
                        @if ($selectedTrack)
                            <input type="hidden" name="track" value="{{ $selectedTrack }}">
                        @endif
                        <input type="search" name="q" value="{{ $searchTerm }}" placeholder="Search sessions, speakers, or topics">
                        <button type="submit" class="button secondary agenda-search-button">Search</button>
                    </form>

                    <form method="GET" action="{{ $agendaBasePath }}" class="agenda-track-inline-form">
                        @if ($selectedDay)
                            <input type="hidden" name="day" value="{{ $selectedDay }}">
                        @endif
                        @if ($searchTerm !== '')
                            <input type="hidden" name="q" value="{{ $searchTerm }}">
                        @endif
                        <label for="track" class="agenda-track-inline-label">Track</label>
                        <select id="track" name="track" class="agenda-track-inline-select">
                            <option value="">All Tracks</option>
                            @foreach ($allTracks as $track)
                                <option value="{{ $track['slug'] }}" @selected($selectedTrack === $track['slug'])>{{ $track['name'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="button secondary agenda-track-inline-button">Apply</button>
                        <a href="{{ $agendaUrl(['track' => null]) }}" class="agenda-clear-link">Clear</a>
                    </form>
                </div>

                @if ($searchTerm !== '' || ! empty($selectedTracks))
                    <div class="agenda-active-filters">
                        @if ($searchTerm !== '')
                            <span class="meta-pill agenda-active-filter-chip">Search: {{ $searchTerm }}</span>
                        @endif
                        @foreach ($allTracks->whereIn('slug', $selectedTracks) as $activeTrack)
                            @php($activeTrackStyle = $trackStyles[$activeTrack['slug']] ?? ['bg' => '#EEF2F8', 'border' => '#D8E0EC', 'ink' => '#223454', 'dot' => '#223454'])
                            <span
                                class="track-pill track-pill--filter"
                                style="--track-bg: {{ $activeTrackStyle['bg'] }}; --track-border: {{ $activeTrackStyle['border'] }}; --track-ink: {{ $activeTrackStyle['ink'] }}; --track-dot: {{ $activeTrackStyle['dot'] }};"
                            >
                                {{ $activeTrack['name'] }}
                            </span>
                        @endforeach
                        <a href="{{ $agendaBasePath }}" class="agenda-clear-link">Reset</a>
                    </div>
                @endif
            </div>

            <section class="card agenda-pulse-bar">
                @if ($schedulePulse['status'] === 'live' && $schedulePulse['currentSessions']->isNotEmpty())
                    <div class="agenda-pulse-bar__content">
                        <div class="agenda-pulse-bar__copy">
                            <span class="agenda-pulse-bar__label">Happening now</span>
                            <strong>{{ $liveCount }} {{ \Illuminate\Support\Str::plural('session', $liveCount) }} live</strong>
                            <span class="muted">as of {{ $schedulePulse['nowLabel'] }}</span>
                        </div>
                        <div class="agenda-pulse-bar__chips">
                            @foreach ($schedulePulse['currentSessions']->take(3) as $liveSession)
                                <a href="{{ $sessionPathPrefix }}/{{ $liveSession->id }}" class="meta-pill">{{ $liveSession->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @elseif ($schedulePulse['nextSession'])
                    <div class="agenda-pulse-bar__content">
                        <div class="agenda-pulse-bar__copy">
                            <span class="agenda-pulse-bar__label">Up next</span>
                            <strong>{{ $schedulePulse['nextSession']->title }}</strong>
                            <span class="muted">{{ $schedulePulse['nextSession']->day->name ?? 'Conference Day' }} at {{ \Illuminate\Support\Str::of($schedulePulse['nextSession']->start_time)->substr(0, 5) }}</span>
                        </div>
                        <a href="{{ $sessionPathPrefix }}/{{ $schedulePulse['nextSession']->id }}" class="button secondary agenda-toolbar-button">Open Session</a>
                    </div>
                @else
                    <div class="agenda-pulse-bar__content">
                        <div class="agenda-pulse-bar__copy">
                            <span class="agenda-pulse-bar__label">Schedule pulse</span>
                            <strong>Nothing live right now</strong>
                            <span class="muted">Use the day tabs or filters to plan ahead.</span>
                        </div>
                    </div>
                @endif
            </section>

            @forelse ($agendaDays as $agendaDay)
                <section class="card stack agenda-day-section @if($isPreviewMode) agenda-day-section--preview @endif">
                    <div class="agenda-day-section__header">
                        <div>
                            <h3 style="margin:0;">{{ $agendaDay['day']->name ?? 'Conference Day' }}</h3>
                            <p class="muted" style="margin:6px 0 0;">{{ $agendaDay['day']->event_date ?? 'Date TBD' }}</p>
                        </div>
                        @unless($isPreviewMode)
                            <div class="agenda-day-section__summary">
                                <span>{{ collect($agendaDay['timeSlots'])->sum(fn ($slot) => $slot['sessions']->count()) }} sessions</span>
                                <span>{{ collect($agendaDay['timeSlots'])->pluck('sessions')->flatten()->pluck('location')->filter()->unique()->count() }} rooms</span>
                            </div>
                        @endunless
                    </div>

                    @if ($isPreviewMode)
                        <div class="agenda-preview-grid">
                            @foreach ($agendaDay['timeSlots'] as $slot)
                                @foreach ($slot['sessions'] as $session)
                                    @php($sessionSpeakers = $session->resolvedSpeakers())
                                    <article class="card agenda-preview-session-card">
                                        <div class="agenda-preview-session-card__top">
                                            <div class="agenda-preview-session-card__time">
                                                <strong>{{ $slot['start'] }}</strong>
                                                <span>to {{ $slot['end'] }}</span>
                                            </div>
                                            <div class="agenda-session-card__meta">
                                                @php($previewTrackStyle = $trackStyles[$session->agenda_track['slug']] ?? ['bg' => '#EEF2F8', 'border' => '#D8E0EC', 'ink' => '#223454', 'dot' => '#223454'])
                                                <span class="track-pill" style="--track-bg: {{ $previewTrackStyle['bg'] }}; --track-border: {{ $previewTrackStyle['border'] }}; --track-ink: {{ $previewTrackStyle['ink'] }}; --track-dot: {{ $previewTrackStyle['dot'] }};">
                                                    {{ $session->agenda_track['name'] }}
                                                </span>
                                                <span class="meta-pill agenda-location-pill">{{ $session->location ?? 'TBD' }}</span>
                                            </div>
                                        </div>

                                        <div class="agenda-preview-session-card__body">
                                            <h4 class="agenda-preview-session-card__title">{{ $session->title }}</h4>

                                            @if ($sessionSpeakers->isNotEmpty())
                                                <div class="agenda-preview-session-card__speakers">
                                                    @foreach ($sessionSpeakers->take(3) as $speaker)
                                                        <div class="agenda-preview-session-card__speaker">
                                                            @if ($speaker->profile_photo_path)
                                                                <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot">
                                                            @else
                                                                <div class="agenda-preview-session-card__speaker-fallback">
                                                                    {{ strtoupper(substr($speaker->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                            <div class="agenda-preview-session-card__speaker-copy">
                                                                <strong>{{ $speaker->name }}</strong>
                                                                @if ($speaker->title || $speaker->organization)
                                                                    <span>{{ collect([$speaker->title, $speaker->organization])->filter()->implode(' · ') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($session->description)
                                                <p class="agenda-preview-session-card__description">{{ \Illuminate\Support\Str::limit($session->description, 180) }}</p>
                                            @endif
                                        </div>

                                        <div class="agenda-preview-session-card__footer">
                                            <a href="{{ $sessionPathPrefix }}/{{ $session->id }}">View details</a>
                                            <a href="{{ $registrationUrl }}" target="_blank" rel="noreferrer" class="button secondary agenda-save-button agenda-save-button--preview">Register</a>
                                        </div>
                                    </article>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        @foreach ($agendaDay['timeSlots'] as $slot)
                            <div class="agenda-slot-group">
                                <div class="agenda-slot">
                                    <div class="card agenda-time-card">
                                        <p class="muted" style="margin:0;">Session {{ $loop->iteration }}</p>
                                        <strong style="display:block; margin-top:6px;">{{ $slot['start'] }}</strong>
                                        <span class="muted">to {{ $slot['end'] }}</span>
                                    </div>

                                    <div class="agenda-session-list">
                                        @foreach ($slot['sessions'] as $session)
                                            @php($sessionSpeakers = $session->resolvedSpeakers())
                                            <article class="card agenda-calendar-card">
                                                <div class="agenda-calendar-card__header">
                                                    <div class="agenda-calendar-card__heading">
                                                        <div class="agenda-session-card__meta">
                                                            @php($trackStyle = $trackStyles[$session->agenda_track['slug']] ?? ['bg' => '#EEF2F8', 'border' => '#D8E0EC', 'ink' => '#223454', 'dot' => '#223454'])
                                                            <span class="track-pill" style="--track-bg: {{ $trackStyle['bg'] }}; --track-border: {{ $trackStyle['border'] }}; --track-ink: {{ $trackStyle['ink'] }}; --track-dot: {{ $trackStyle['dot'] }};">
                                                                {{ $session->agenda_track['name'] }}
                                                            </span>
                                                            <span class="meta-pill agenda-location-pill">{{ $session->location ?? 'TBD' }}</span>
                                                        </div>
                                                        <h4 class="agenda-session-card__title">{{ $session->title }}</h4>
                                                    </div>
                                                    <form method="POST" action="/sessions/{{ $session->id }}/toggle-save">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="button secondary agenda-save-button @if(! in_array($session->id, $savedSessionIds, true)) agenda-save-button--add @endif"
                                                        >
                                                            {{ in_array($session->id, $savedSessionIds, true) ? 'Saved' : 'Save' }}
                                                        </button>
                                                    </form>
                                                </div>

                                                @if ($sessionSpeakers->isNotEmpty())
                                                    <div class="agenda-session-card__speaker-preview-list">
                                                        @foreach ($sessionSpeakers->take(3) as $speaker)
                                                            <a href="/speakers/{{ $speaker->id }}" class="agenda-session-card__speaker-preview">
                                                                @if ($speaker->profile_photo_path)
                                                                    <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot">
                                                                @else
                                                                    <div class="agenda-session-card__speaker-preview-fallback">
                                                                        {{ strtoupper(substr($speaker->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div class="agenda-session-card__speaker-preview-copy">
                                                                    <strong>{{ $speaker->name }}</strong>
                                                                    <span>{{ collect([$speaker->title, $speaker->organization])->filter()->implode(' · ') ?: 'Speaker' }}</span>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                        @if ($sessionSpeakers->count() > 3)
                                                            <span class="agenda-session-card__speaker-count">+{{ $sessionSpeakers->count() - 3 }} more</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if ($session->description)
                                                    <p class="agenda-session-card__description">{{ \Illuminate\Support\Str::limit($session->description, 200) }}</p>
                                                @endif

                                                <div class="agenda-calendar-card__footer">
                                                    <div class="agenda-calendar-card__slot-meta">
                                                        <span>{{ $slot['start'] }} - {{ $slot['end'] }}</span>
                                                        <span>{{ $sessionSpeakers->count() > 1 ? $sessionSpeakers->count().' speakers' : ($sessionSpeakers->count() === 1 ? '1 speaker' : 'Open session') }}</span>
                                                    </div>
                                                    <a href="{{ $sessionPathPrefix }}/{{ $session->id }}">View details</a>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </section>
            @empty
                <p class="muted">No sessions are available yet.</p>
            @endforelse
        </section>
    </div>

    <style>
        .agenda-shell {
            gap: 14px;
        }

        .agenda-discovery-shell {
            display: grid;
            gap: 12px;
        }

        .agenda-control-bar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: end;
            padding: 14px;
            border-radius: 20px;
        }

        .agenda-search-row {
            display: grid;
            gap: 10px;
        }

        .agenda-search-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: stretch;
        }

        .agenda-search-form input[type="search"] {
            min-height: 48px;
            border-radius: 16px;
            border: 1px solid rgba(33, 43, 73, 0.12);
            padding: 0 16px;
            background: rgba(255, 255, 255, 0.92);
            color: #18233f;
            font: inherit;
        }

        .agenda-search-button {
            min-width: 112px;
        }

        .agenda-track-inline-form {
            display: grid;
            grid-template-columns: auto minmax(180px, 220px) auto auto;
            gap: 10px;
            align-items: center;
        }

        .agenda-track-inline-label {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--brand-muted);
        }

        .agenda-track-inline-select {
            min-height: 48px;
            width: 100%;
            border-radius: 16px;
            border: 1px solid rgba(33, 43, 73, 0.12);
            padding: 0 14px;
            background: rgba(255, 255, 255, 0.94);
            color: #18233f;
            font: inherit;
        }

        .agenda-track-inline-button {
            min-width: 92px;
        }

        .agenda-active-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .agenda-active-filter-chip {
            background: rgba(24, 42, 84, 0.06);
            color: #223454;
        }

        .agenda-clear-link {
            font-size: 0.92rem;
            font-weight: 700;
            color: #8a2844;
            text-decoration: none;
        }

        .track-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 32px;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--track-bg);
            border: 1px solid var(--track-border);
            color: var(--track-ink);
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1;
        }

        .track-pill::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--track-dot);
            flex: 0 0 auto;
        }

        .track-pill--panel {
            min-height: 30px;
            font-size: 0.82rem;
        }

        .agenda-location-pill {
            background: rgba(24, 42, 84, 0.05);
            border-color: rgba(33, 43, 73, 0.08);
            color: #415170;
        }

        .agenda-preview-feature-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .agenda-preview-feature-card {
            display: grid;
            gap: 16px;
            padding: 20px;
            text-decoration: none;
            color: inherit;
            background:
                radial-gradient(circle at top right, rgba(255, 125, 142, 0.16), transparent 32%),
                linear-gradient(160deg, rgba(20, 33, 72, 0.98), rgba(11, 17, 34, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 42px rgba(10, 14, 28, 0.22);
            border-radius: 24px;
        }

        .agenda-preview-feature-card .eyebrow,
        .agenda-preview-feature-card .muted {
            color: rgba(238, 243, 250, 0.78);
        }

        .agenda-preview-feature-card__top,
        .agenda-preview-feature-card__footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .agenda-preview-feature-card__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: flex-end;
        }

        .agenda-preview-feature-card__meta .meta-pill {
            color: rgba(244, 248, 252, 0.88);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .agenda-preview-feature-card__body {
            display: grid;
            gap: 10px;
        }

        .agenda-preview-feature-card__body h3 {
            margin: 0;
            color: #fff;
            line-height: 1.06;
            letter-spacing: -0.04em;
            font-size: clamp(1.35rem, 2vw, 1.9rem);
        }

        .agenda-preview-feature-card__speaker {
            display: grid;
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 12px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .agenda-preview-feature-card__speaker img,
        .agenda-preview-feature-card__speaker-fallback {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            object-fit: cover;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            background: var(--button-gradient);
        }

        .agenda-preview-feature-card__speaker strong,
        .agenda-preview-feature-card__speaker span {
            display: block;
        }

        .agenda-preview-feature-card__speaker strong {
            color: #fff;
        }

        .agenda-preview-feature-card__speaker span {
            color: rgba(238, 243, 250, 0.76);
            font-size: 0.84rem;
            line-height: 1.35;
        }

        .agenda-preview-feature-card__footer {
            color: rgba(238, 243, 250, 0.76);
            font-size: 0.84rem;
            padding-top: 2px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .agenda-preview-feature-card__footer strong {
            color: #fff;
            font-size: 0.92rem;
            letter-spacing: -0.01em;
        }

        .agenda-preview-spotlight__intro {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(240px, 0.8fr);
            gap: 14px;
            align-items: end;
        }

        .agenda-preview-speaker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 10px;
        }

        .agenda-preview-speaker-card {
            display: grid;
            grid-template-columns: 60px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
            padding: 12px;
            border-radius: 18px;
            text-decoration: none;
            color: inherit;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 34px rgba(8, 12, 24, 0.18);
        }

        .agenda-preview-speaker-card__avatar img,
        .agenda-preview-speaker-card__fallback {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            object-fit: cover;
            display: grid;
            place-items: center;
            font-weight: 800;
            color: #fff;
            background: var(--button-gradient);
            box-shadow: 0 12px 24px rgba(8, 12, 24, 0.24);
        }

        .agenda-preview-speaker-card__copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .agenda-preview-speaker-card__copy strong,
        .agenda-preview-speaker-card__copy span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .agenda-preview-speaker-card__copy span:last-child {
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.92rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .agenda-day-tabs {
            margin-bottom: 2px;
        }

        .agenda-pulse-bar {
            padding: 12px 14px;
        }

        .agenda-pulse-bar__content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .agenda-pulse-bar__copy {
            display: grid;
            gap: 4px;
        }

        .agenda-pulse-bar__label {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #8a2844;
        }

        .agenda-pulse-bar__chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .agenda-toolbar-button {
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }

        .agenda-filter-drawer {
            padding: 0;
            overflow: hidden;
        }

        .agenda-filter-drawer__summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            cursor: pointer;
            list-style: none;
        }

        .agenda-filter-drawer__summary::-webkit-details-marker {
            display: none;
        }

        .agenda-filter-drawer__chevron {
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

        .agenda-filter-drawer[open] .agenda-filter-drawer__summary {
            border-bottom: 1px solid var(--brand-line);
        }

        .agenda-filter-drawer[open] .agenda-filter-drawer__chevron {
            transform: rotate(45deg);
        }

        .agenda-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            align-items: end;
            padding: 14px 16px 16px;
        }

        .agenda-filter-actions {
            display: flex;
            gap: 12px;
            align-items: stretch;
            width: 100%;
        }

        .agenda-filter-actions > * {
            flex: 1 1 0;
        }

        .button.agenda-filter-button,
        .agenda-filter-actions .button,
        .agenda-save-button {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            line-height: 1.2;
            white-space: nowrap;
            border-radius: 999px;
            text-align: center;
        }

        .agenda-save-button {
            width: auto;
            min-width: 88px;
            padding-inline: 16px;
        }

        .agenda-save-button--preview {
            text-decoration: none;
        }

        .button.secondary.agenda-save-button--add {
            background: linear-gradient(135deg, rgba(229, 242, 237, 0.98), rgba(244, 250, 247, 1));
            border-color: rgba(163, 194, 183, 0.55);
            color: #23443d;
            box-shadow: 0 12px 24px rgba(110, 154, 140, 0.1);
        }

        .button.secondary.agenda-save-button--add:hover {
            background: linear-gradient(135deg, rgba(221, 238, 231, 1), rgba(239, 248, 244, 1));
            color: #1c3832;
        }

        .agenda-day-section {
            gap: 14px;
        }

        .agenda-day-section--preview {
            padding: 18px;
            background:
                radial-gradient(circle at top right, rgba(255, 125, 142, 0.08), transparent 36%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(252, 246, 248, 0.98));
        }

        .agenda-day-section__header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .agenda-day-section__summary {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--brand-muted);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .agenda-day-section__summary span {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 6px 11px;
            border-radius: 999px;
            background: var(--stat-bg);
            border: 1px solid var(--brand-line);
        }

        .agenda-slot-group {
            border-top: 1px solid var(--brand-line);
            padding-top: 14px;
        }

        .agenda-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 14px;
        }

        .agenda-preview-session-card {
            display: grid;
            gap: 14px;
            padding: 18px;
            border-radius: 24px;
            background:
                radial-gradient(circle at top right, rgba(255, 125, 142, 0.08), transparent 32%),
                linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(250, 244, 247, 0.98));
            box-shadow: 0 18px 36px rgba(14, 20, 40, 0.08);
        }

        .agenda-preview-session-card__top,
        .agenda-preview-session-card__footer {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .agenda-preview-session-card__time {
            display: grid;
            gap: 2px;
        }

        .agenda-preview-session-card__time strong {
            font-size: 1.1rem;
            letter-spacing: -0.03em;
        }

        .agenda-preview-session-card__time span {
            color: var(--brand-muted);
            font-size: 0.85rem;
        }

        .agenda-preview-session-card__body {
            display: grid;
            gap: 14px;
        }

        .agenda-preview-session-card__title {
            margin: 0;
            line-height: 1.08;
            letter-spacing: -0.03em;
            font-size: clamp(1.2rem, 2vw, 1.5rem);
        }

        .agenda-preview-session-card__speakers {
            display: grid;
            gap: 10px;
        }

        .agenda-preview-session-card__speaker {
            display: grid;
            grid-template-columns: 72px minmax(0, 1fr);
            gap: 14px;
            align-items: center;
        }

        .agenda-preview-session-card__speaker img,
        .agenda-preview-session-card__speaker-fallback {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            object-fit: cover;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            background: var(--button-gradient);
            box-shadow: 0 10px 20px rgba(17, 23, 48, 0.16);
        }

        .agenda-preview-session-card__speaker-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .agenda-preview-session-card__speaker-copy strong {
            line-height: 1.15;
        }

        .agenda-preview-session-card__speaker-copy span {
            color: var(--brand-muted);
            font-size: 0.84rem;
            line-height: 1.35;
        }

        .agenda-preview-session-card__description {
            margin: 0;
            color: var(--brand-muted);
            line-height: 1.55;
        }

        .agenda-slot {
            display: grid;
            grid-template-columns: 128px 1fr;
            gap: 12px;
            align-items: start;
        }

        .agenda-time-card {
            position: sticky;
            top: 92px;
            padding: 12px;
            border-radius: 16px;
        }

        .agenda-session-list {
            display: grid;
            gap: 10px;
        }

        .agenda-calendar-card {
            display: grid;
            gap: 12px;
            padding: 16px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(255, 244, 247, 0.98));
            border: 1px solid rgba(33, 43, 73, 0.08);
            box-shadow: 0 12px 26px rgba(14, 20, 40, 0.06);
            color: #18233f;
            position: relative;
            overflow: hidden;
        }

        html[data-theme="dark"] .agenda-calendar-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(255, 244, 247, 0.98));
            border-color: rgba(33, 43, 73, 0.08);
            box-shadow: 0 12px 26px rgba(14, 20, 40, 0.08);
            color: #18233f;
        }

        .agenda-calendar-card__header {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: start;
        }

        .agenda-calendar-card__heading {
            display: grid;
            gap: 4px;
            min-width: 0;
            padding-top: 2px;
        }

        .agenda-session-card__meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .agenda-calendar-card .meta-pill {
            background: rgba(194, 224, 214, 0.26);
            border-color: rgba(33, 43, 73, 0.08);
            color: #223454;
        }

        html[data-theme="dark"] .agenda-calendar-card .meta-pill {
            background: rgba(194, 224, 214, 0.26);
            border-color: rgba(33, 43, 73, 0.08);
            color: #223454;
        }

        .agenda-session-card__title {
            margin: 0;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: #18233f;
        }

        .agenda-session-card__speaker-preview-list {
            display: grid;
            gap: 6px;
        }

        .agenda-session-card__speaker-preview {
            display: grid;
            grid-template-columns: 62px minmax(0, 1fr);
            gap: 10px;
            align-items: center;
            min-width: 0;
            padding: 2px 0;
            border-radius: 0;
            background: transparent;
            border: 0;
            text-decoration: none;
            color: #18233f;
        }

        html[data-theme="dark"] .agenda-session-card__speaker-preview {
            background: transparent;
            border-color: transparent;
            color: #18233f;
        }

        .agenda-session-card__speaker-preview img,
        .agenda-session-card__speaker-preview-fallback {
            width: 62px;
            height: 62px;
            border-radius: 14px;
            object-fit: cover;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            background: var(--button-gradient);
            box-shadow: 0 10px 18px rgba(17, 23, 48, 0.16);
        }

        .agenda-session-card__speaker-preview-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .agenda-session-card__speaker-preview-copy strong,
        .agenda-session-card__speaker-preview-copy span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .agenda-session-card__speaker-preview-copy span {
            color: #415170;
            font-size: 0.8rem;
            line-height: 1.3;
            white-space: normal;
        }

        .agenda-session-card__speaker-count {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0 12px;
            border-radius: 999px;
            background: rgba(24, 42, 84, 0.03);
            border: 1px dashed rgba(33, 43, 73, 0.14);
            color: #415170;
            font-size: 0.84rem;
            font-weight: 700;
        }

        html[data-theme="dark"] .agenda-session-card__speaker-count {
            background: rgba(24, 42, 84, 0.05);
            border-color: rgba(33, 43, 73, 0.08);
            color: #415170;
        }

        .agenda-session-card__description {
            margin: 0;
            color: #223454;
            line-height: 1.45;
        }

        .agenda-calendar-card__footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            padding-top: 2px;
            border-top: 1px solid var(--brand-line);
        }

        html[data-theme="dark"] .agenda-calendar-card__footer {
            border-top-color: var(--brand-line);
        }

        .agenda-calendar-card__slot-meta {
            display: flex;
            gap: 6px 10px;
            flex-wrap: wrap;
            color: #415170;
            font-size: 0.86rem;
        }


        @media (max-width: 720px) {
            .agenda-page-shell {
                padding: 0;
                border-radius: 0;
                background: transparent;
                border: 0;
                box-shadow: none;
            }

            .agenda-shell {
                padding: 12px;
                border-radius: 18px;
                gap: 12px;
            }

            .agenda-control-bar {
                grid-template-columns: 1fr;
                padding: 12px;
                border-radius: 16px;
            }

            .agenda-track-inline-form {
                grid-template-columns: 1fr;
                align-items: stretch;
                gap: 8px;
            }

            .agenda-search-form {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .agenda-preview-feature-row {
                grid-template-columns: 1fr;
            }

            .agenda-day-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-inline: 4px;
                scrollbar-width: none;
            }

            .agenda-day-tabs::-webkit-scrollbar {
                display: none;
            }

            .agenda-day-tabs .section-tab {
                flex: 0 0 auto;
                min-width: max-content;
            }

            .agenda-slot {
                grid-template-columns: 1fr;
                align-items: stretch;
                gap: 8px;
            }

            .agenda-pulse-bar__content,
            .agenda-calendar-card__header,
            .agenda-calendar-card__footer {
                display: grid;
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .agenda-time-card {
                position: static;
                padding: 10px 12px;
            }

            .agenda-filters {
                grid-template-columns: 1fr;
            }

            .agenda-filter-actions {
                flex-direction: column;
            }

            .agenda-save-button {
                width: 100%;
            }

            .agenda-day-section {
                padding: 12px;
                border-radius: 16px;
                gap: 12px;
            }

            .agenda-calendar-card,
            .agenda-preview-session-card {
                padding: 14px;
                border-radius: 18px;
            }

            .agenda-session-card__speaker-preview {
                grid-template-columns: 68px minmax(0, 1fr);
                gap: 12px;
            }

            .agenda-session-card__speaker-preview img,
            .agenda-session-card__speaker-preview-fallback {
                width: 68px;
                height: 68px;
                border-radius: 16px;
            }

            .agenda-day-section__summary {
                width: 100%;
            }
        }
    </style>
@endsection
