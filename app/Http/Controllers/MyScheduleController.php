<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class MyScheduleController extends Controller
{
    public function __invoke()
    {
        $sessions = auth()->user()
            ->savedSessions()
            ->with(['day', 'track', 'speaker', 'speakers'])
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        $scheduleDays = $sessions
            ->groupBy(fn ($session) => $session->day?->id ?? 'none')
            ->map(function ($daySessions) {
                return [
                    'day' => $daySessions->first()->day,
                    'sessions' => $daySessions->values(),
                ];
            })
            ->values();

        $conflicts = collect();

        foreach ($scheduleDays as $scheduleDay) {
            $previousSession = null;

            foreach ($scheduleDay['sessions'] as $session) {
                if (! $previousSession || ! $previousSession->start_time || ! $previousSession->end_time || ! $session->start_time || ! $session->end_time) {
                    $previousSession = $session;
                    continue;
                }

                $previousEnd = Carbon::parse($previousSession->end_time);
                $currentStart = Carbon::parse($session->start_time);

                if ($currentStart->lt($previousEnd)) {
                    $conflicts->push([
                        'day' => $scheduleDay['day'],
                        'first' => $previousSession,
                        'second' => $session,
                    ]);
                }

                if (Carbon::parse($session->end_time)->gt($previousEnd)) {
                    $previousSession = $session;
                }
            }
        }

        return view('my-schedule.index', compact('sessions', 'scheduleDays', 'conflicts'));
    }
}
