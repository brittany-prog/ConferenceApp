<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Session;
use App\Services\EngagementService;
use App\Support\AppSettings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    private const FEATURED_PREVIEW_SPEAKERS = [
        'Krystal Chatman',
        'Loretta Moore',
        'Chris Chism',
    ];

    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function index(Request $request)
    {
        return view('sessions.index', $this->buildAgendaViewData($request, auth()->user()?->savedSessions()->pluck('sessions.id')->all() ?? []));
    }

    public function publicIndex(Request $request)
    {
        return view('sessions.index', $this->buildAgendaViewData($request, [], true));
    }

    public function show(Session $session)
    {
        return view('sessions.show', $this->buildSessionDetailViewData($session));
    }

    public function publicShow(Session $session)
    {
        return view('sessions.show', $this->buildSessionDetailViewData($session, true));
    }

    public function toggleSave(Session $session)
    {
        $user = auth()->user();

        if ($user->savedSessions()->where('sessions.id', $session->id)->exists()) {
            $user->savedSessions()->detach($session->id);
        } else {
            $user->savedSessions()->attach($session->id);
            $this->engagementService->awardOnce(
                $user,
                'session_saved',
                5,
                'session',
                $session->id,
                'Saved a session to your personal schedule.'
            );
        }

        return back();
    }

    private function buildSchedulePulse($sessions): array
    {
        $now = Carbon::now(config('app.timezone'));

        $scheduledSessions = $sessions
            ->filter(fn ($session) => $session->day?->event_date && $session->start_time && $session->end_time)
            ->map(function ($session) use ($now) {
                $startAt = Carbon::parse($session->day->event_date.' '.$session->start_time, $now->timezone);
                $endAt = Carbon::parse($session->day->event_date.' '.$session->end_time, $now->timezone);

                return [
                    'session' => $session,
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                ];
            })
            ->sortBy('start_at')
            ->values();

        $currentSessions = $scheduledSessions
            ->filter(fn ($item) => $item['start_at']->lte($now) && $item['end_at']->gt($now))
            ->pluck('session')
            ->values();

        $nextScheduledItem = $scheduledSessions->first(fn ($item) => $item['start_at']->gt($now));
        $nextSession = $nextScheduledItem['session'] ?? null;

        $status = 'upcoming';

        if ($currentSessions->isNotEmpty()) {
            $status = 'live';
        } elseif ($scheduledSessions->isNotEmpty() && $scheduledSessions->last()['end_at']->lte($now)) {
            $status = 'ended';
        }

        return [
            'status' => $status,
            'currentSessions' => $currentSessions,
            'nextSession' => $nextSession,
            'nowLabel' => $now->format('M j, g:i A'),
        ];
    }

    private function buildAgendaViewData(Request $request, array $savedSessionIds = [], bool $isPreviewMode = false): array
    {
        $settings = AppSettings::all();
        $selectedDay = $request->integer('day') ?: null;
        $selectedTrack = trim((string) $request->string('track'));
        $selectedTracks = collect($request->input('tracks', []))
            ->map(fn ($trackId) => (string) $trackId)
            ->filter()
            ->unique()
            ->values()
            ->all();
        if ($selectedTrack !== '' && ! in_array($selectedTrack, $selectedTracks, true)) {
            array_unshift($selectedTracks, $selectedTrack);
        }
        $searchTerm = trim((string) $request->string('q'));

        $allDays = Day::query()
            ->orderBy('sort_order')
            ->orderBy('event_date')
            ->get();

        $sessions = Session::with(['day', 'track', 'speaker', 'speakers'])
            ->when($selectedDay, fn ($query) => $query->where('day_id', $selectedDay))
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $like = '%'.$searchTerm.'%';

                $query->where(function (Builder $nested) use ($like) {
                    $nested
                        ->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhereHas('speaker', fn (Builder $speakerQuery) => $speakerQuery->where('name', 'like', $like))
                        ->orWhereHas('speakers', fn (Builder $speakerQuery) => $speakerQuery->where('name', 'like', $like));
                });
            })
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->orderBy('track_id')
            ->get();

        $sessions->each(function ($session) {
            $session->agenda_track = $this->inferAgendaTrack($session);
        });

        $allTracks = Session::agendaTrackOptions();

        if (! empty($selectedTracks)) {
            $sessions = $sessions
                ->filter(fn ($session) => in_array($session->agenda_track['slug'], $selectedTracks, true))
                ->values();
        }

        $agendaDays = $sessions
            ->groupBy(fn ($session) => $session->day?->id ?? 'none')
            ->map(function ($daySessions) {
                $day = $daySessions->first()->day;

                return [
                    'day' => $day,
                    'timeSlots' => $daySessions
                        ->groupBy(fn ($session) => ($session->start_time ?? 'TBD').'|'.($session->end_time ?? 'TBD'))
                        ->map(function ($slotSessions, $slotKey) {
                            [$start, $end] = explode('|', $slotKey);

                            return [
                                'start' => $start,
                                'end' => $end,
                                'sessions' => $slotSessions->sortBy(fn ($session) => $session->agenda_track['name'] ?? 'ZZZ')->values(),
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();

        $featuredPreviewSessions = collect(self::FEATURED_PREVIEW_SPEAKERS)
            ->map(function ($targetSpeaker) use ($sessions) {
                $normalizedTarget = $this->normalizeSpeakerName($targetSpeaker);

                return $sessions->first(function ($session) use ($normalizedTarget) {
                    return $session->resolvedSpeakers()->contains(
                        fn ($speaker) => $this->normalizeSpeakerName($speaker->name) === $normalizedTarget
                    );
                });
            })
            ->filter()
            ->unique('id')
            ->values();

        if ($featuredPreviewSessions->count() < 3) {
            $featuredPreviewSessions = $featuredPreviewSessions
                ->concat(
                    $sessions
                        ->reject(fn ($session) => $featuredPreviewSessions->contains('id', $session->id))
                        ->take(3 - $featuredPreviewSessions->count())
                )
                ->values();
        }

        $trackStyles = Session::agendaTrackOptions()
            ->mapWithKeys(fn ($track) => [$track['slug'] => $track['style']])
            ->all();

        return [
            'sessions' => $sessions,
            'agendaDays' => $agendaDays,
            'allDays' => $allDays,
            'allTracks' => $allTracks,
            'selectedDay' => $selectedDay,
            'selectedTrack' => $selectedTracks[0] ?? null,
            'selectedTracks' => $selectedTracks,
            'searchTerm' => $searchTerm,
            'savedSessionIds' => $savedSessionIds,
            'schedulePulse' => $this->buildSchedulePulse($sessions),
            'isPreviewMode' => $isPreviewMode,
            'registrationUrl' => $settings['public_ticket_url'] ?? null,
            'featuredPreviewSessions' => $featuredPreviewSessions,
            'trackStyles' => $trackStyles,
        ];
    }

    private function inferAgendaTrack(Session $session): array
    {
        if ($session->agenda_track_type) {
            return Session::agendaTrackForSlug($session->agenda_track_type)
                ?? Session::agendaTrackForSlug('research-innovation');
        }

        $haystack = str(collect([
            $session->title,
            $session->description,
            $session->session_type,
            $session->speaker?->organization,
        ])->filter()->implode(' '))
            ->lower()
            ->squish()
            ->value();

        $taxonomy = [
            array_merge(Session::agendaTrackForSlug('featured-keynotes'), ['keywords' => ['keynote', 'welcome', 'orientation', 'featured']]),
            array_merge(Session::agendaTrackForSlug('education-workforce'), ['keywords' => ['classroom', 'teacher', 'k-12', 'school', 'student', 'education', 'literacy', 'training', 'workforce', 'khan', 'khanmigo']]),
            array_merge(Session::agendaTrackForSlug('ai-tools-build'), ['keywords' => ['llm', 'claude', 'notebooklm', 'build', 'workflow', 'tools', 'technical', 'developer', 'code', 'systems']]),
            array_merge(Session::agendaTrackForSlug('business-entrepreneurship'), ['keywords' => ['business', 'entrepreneur', 'customer discovery', 'value', 'startup', 'earning', 'operator', 'innovation program']]),
            array_merge(Session::agendaTrackForSlug('community-policy'), ['keywords' => ['community', 'policy', 'social impact', 'public', 'state', 'department', 'regional', 'ecosystem']]),
            array_merge(Session::agendaTrackForSlug('networking-experience'), ['keywords' => ['networking', 'break', 'coffee', 'lunch', 'poster']]),
        ];

        foreach ($taxonomy as $track) {
            foreach ($track['keywords'] as $keyword) {
                if ($haystack !== '' && str_contains($haystack, $keyword)) {
                    return collect($track)->except('keywords')->all();
                }
            }
        }

        return Session::agendaTrackForSlug('research-innovation');
    }

    private function normalizeSpeakerName(string $name): string
    {
        $normalized = str($name)
            ->lower()
            ->replaceMatches('/\bdr\.?\s+/i', '')
            ->replaceMatches('/\s+/', ' ')
            ->trim();

        return (string) $normalized;
    }

    private function buildSessionDetailViewData(Session $session, bool $isPreviewMode = false): array
    {
        $session->load([
            'day',
            'track',
            'speaker',
            'speakers',
            'feedback.user',
        ]);

        $feedbackSummary = [
            'count' => $session->feedback->count(),
            'average' => $session->feedback->avg('rating'),
        ];

        return [
            'session' => $session,
            'feedbackSummary' => $feedbackSummary,
            'userFeedback' => $isPreviewMode ? null : $session->feedback->firstWhere('user_id', auth()->id()),
            'isPreviewMode' => $isPreviewMode,
            'registrationUrl' => self::REGISTRATION_URL,
        ];
    }
}
