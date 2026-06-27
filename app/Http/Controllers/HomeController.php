<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\CommunityTopic;
use App\Models\Day;
use App\Models\Message;
use App\Models\Session;
use App\Models\Sponsor;
use App\Models\User;
use App\Services\EngagementService;
use App\Support\AppSettings;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function landing()
    {
        if (auth()->check()) {
            return redirect('/app');
        }

        $sponsors = Sponsor::query()
            ->where('is_active', true)
            ->withCount('exhibitors')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('landing', [
            'daysCount' => Day::count(),
            'sessionsCount' => Session::count(),
            'speakersCount' => User::where('is_speaker', true)->count(),
            'announcements' => Announcement::latest()->take(3)->get(),
            'sponsors' => $sponsors,
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now(config('app.timezone'));
        $settings = AppSettings::all();
        $eventStartDate = ! empty($settings['event_start_date'])
            ? Carbon::parse($settings['event_start_date'], $now->timezone)->startOfDay()
            : Day::query()->whereNotNull('event_date')->orderBy('event_date')->value('event_date');
        $eventStartDate = $eventStartDate instanceof Carbon
            ? $eventStartDate
            : ($eventStartDate ? Carbon::parse($eventStartDate, $now->timezone)->startOfDay() : null);
        $sparkCountdownLabel = null;

        if ($eventStartDate) {
            $daysUntilSpark = (int) $now->copy()->startOfDay()->diffInDays($eventStartDate, false);
            $eventLabel = $settings['brand_name'] ?? 'The event';

            if ($daysUntilSpark > 1) {
                $sparkCountdownLabel = $daysUntilSpark.' days until '.$eventLabel.'!';
            } elseif ($daysUntilSpark === 1) {
                $sparkCountdownLabel = $eventLabel.' starts tomorrow!';
            } elseif ($daysUntilSpark === 0) {
                $sparkCountdownLabel = $eventLabel.' starts today!';
            } else {
                $sparkCountdownLabel = $eventLabel.' is underway!';
            }
        }

        $savedSessions = $user->savedSessions()
            ->with(['day', 'track', 'speaker', 'speakers'])
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        $savedSessionIds = $savedSessions->pluck('id');

        $todaysSavedSessions = $savedSessions
            ->filter(fn ($session) => $session->day?->event_date === $now->toDateString())
            ->values();

        $nextSavedSession = $savedSessions
            ->filter(fn ($session) => $session->day?->event_date && $session->start_time)
            ->map(function ($session) use ($now) {
                $startAt = Carbon::parse($session->day->event_date.' '.$session->start_time, $now->timezone);

                return [
                    'session' => $session,
                    'start_at' => $startAt,
                ];
            })
            ->filter(fn ($item) => $item['start_at']->gt($now))
            ->sortBy('start_at')
            ->first();

        $liveSessions = Session::query()
            ->with(['day', 'track', 'speaker', 'speakers'])
            ->get()
            ->filter(function ($session) use ($now) {
                if (! $session->day?->event_date || ! $session->start_time || ! $session->end_time) {
                    return false;
                }

                $startAt = Carbon::parse($session->day->event_date.' '.$session->start_time, $now->timezone);
                $endAt = Carbon::parse($session->day->event_date.' '.$session->end_time, $now->timezone);

                return $startAt->lte($now) && $endAt->gt($now);
            })
            ->sortBy('start_time')
            ->values();

        $unreadMessagesCount = Message::query()
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $sparkSummary = $this->engagementService->buildUserSummary($user);
        $communityTopics = CommunityTopic::query()
            ->where('is_active', true)
            ->withCount(['posts as published_posts_count' => fn ($query) => $query->where('status', 'published')])
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $speakerShareSession = Session::query()
            ->with(['day', 'track', 'speakers'])
            ->where(function ($query) use ($user) {
                $query->where('speaker_user_id', $user->id)
                    ->orWhereHas('speakers', fn ($nested) => $nested->where('users.id', $user->id));
            })
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->first();

        $sponsors = Sponsor::query()
            ->where('is_active', true)
            ->withCount('exhibitors')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('home', [
            'daysCount' => Day::count(),
            'sessionsCount' => Session::count(),
            'speakersCount' => User::where('is_speaker', true)->count(),
            'announcements' => Announcement::latest()->take(3)->get(),
            'sponsors' => $sponsors,
            'liveSessions' => $liveSessions,
            'todaysSavedSessions' => $todaysSavedSessions,
            'nextSavedSession' => $nextSavedSession['session'] ?? null,
            'savedSessionsCount' => $savedSessionIds->count(),
            'unreadMessagesCount' => $unreadMessagesCount,
            'todayLabel' => $now->format('l, M j'),
            'sparkCountdownLabel' => $sparkCountdownLabel,
            'sparkSummary' => $sparkSummary,
            'communityTopics' => $communityTopics,
            'speakerShareSession' => $speakerShareSession,
        ]);
    }
}
