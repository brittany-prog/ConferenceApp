<?php

namespace App\Support;

use App\Models\Announcement;
use App\Models\Message;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationCenter
{
    public static function for(User $user): array
    {
        $now = Carbon::now(config('app.timezone'));

        $messageNotifications = Message::query()
            ->with('sender')
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->latest()
            ->get()
            ->groupBy('sender_id')
            ->map(function (Collection $messages) {
                $latest = $messages->first();

                return [
                    'type' => 'message',
                    'title' => $messages->count().' new message'.($messages->count() > 1 ? 's' : ''),
                    'body' => 'From '.$latest->sender->name.': '.str($latest->body)->limit(90),
                    'link' => '/messages/'.$latest->sender_id,
                    'timestamp' => $latest->created_at,
                ];
            })
            ->values();

        $announcementNotifications = Announcement::query()
            ->latest()
            ->take(3)
            ->get()
            ->filter(fn ($announcement) => $announcement->created_at?->gte($now->copy()->subDays(14)))
            ->map(function ($announcement) {
                return [
                    'type' => 'announcement',
                    'title' => 'New announcement',
                    'body' => $announcement->title,
                    'link' => '/announcements',
                    'timestamp' => $announcement->created_at,
                ];
            })
            ->values();

        $upcomingSavedSessions = Session::query()
            ->with(['day', 'track'])
            ->whereIn('id', $user->savedSessions()->pluck('sessions.id'))
            ->get()
            ->filter(function ($session) use ($now) {
                if (! $session->day?->event_date || ! $session->start_time) {
                    return false;
                }

                $startAt = Carbon::parse($session->day->event_date.' '.$session->start_time, $now->timezone);

                return $startAt->betweenIncluded($now, $now->copy()->addHours(12));
            })
            ->sortBy(function ($session) use ($now) {
                return Carbon::parse($session->day->event_date.' '.$session->start_time, $now->timezone);
            })
            ->take(3)
            ->map(function ($session) {
                return [
                    'type' => 'session',
                    'title' => 'Upcoming saved session',
                    'body' => $session->title.' starts at '.$session->start_time,
                    'link' => '/sessions/'.$session->id,
                    'timestamp' => Carbon::parse($session->day->event_date.' '.$session->start_time, config('app.timezone')),
                ];
            })
            ->values();

        $items = collect()
            ->concat($messageNotifications)
            ->concat($announcementNotifications)
            ->concat($upcomingSavedSessions)
            ->sortByDesc('timestamp')
            ->values();

        return [
            'count' => $items->count(),
            'items' => $items,
            'preview' => $items->take(3)->values(),
        ];
    }
}
