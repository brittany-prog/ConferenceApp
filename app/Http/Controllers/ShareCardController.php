<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Services\ShareCardService;
use App\Support\AppSettings;
use Illuminate\Http\Request;

class ShareCardController extends Controller
{
    public function __construct(protected ShareCardService $shareCards)
    {
    }

    public function attendee(Request $request)
    {
        $user = $request->user();

        return view('share.attendee', [
            'cardUrl' => route('share.attendee.image'),
            'downloadUrl' => route('share.attendee.image', ['download' => 1]),
            'caption' => $this->shareCards->attendeeCaption($user),
        ]);
    }

    public function attendeeImage(Request $request)
    {
        $svg = $this->shareCards->attendeeSvg($request->user());

        $headers = ['Content-Type' => 'image/svg+xml; charset=UTF-8'];

        if ($request->boolean('download')) {
            $headers['Content-Disposition'] = 'attachment; filename="'.(AppSettings::all()['brand_slug'] ?? 'event-app').'-attendee-card.svg"';
        }

        return response($svg, 200, $headers);
    }

    public function speakerIndex(Request $request)
    {
        $user = $request->user();

        $sessions = Session::query()
            ->with(['day', 'track', 'speakers'])
            ->where(function ($query) use ($user) {
                $query->where('speaker_user_id', $user->id)
                    ->orWhereHas('speakers', fn ($nested) => $nested->where('users.id', $user->id));
            })
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        abort_unless($user->is_speaker || $user->is_admin || $sessions->isNotEmpty(), 403);

        return view('share.speaker-index', [
            'sessions' => $sessions,
        ]);
    }

    public function speaker(Request $request, Session $session)
    {
        $session->load(['day', 'speakers']);
        $speaker = $this->authorizedSpeaker($request, $session);

        return view('share.speaker', [
            'speaker' => $speaker,
            'session' => $session,
            'cardUrl' => route('share.speaker.image', $session),
            'downloadUrl' => route('share.speaker.image', ['session' => $session, 'download' => 1]),
            'caption' => $this->shareCards->speakerCaption($speaker, $session),
        ]);
    }

    public function speakerImage(Request $request, Session $session)
    {
        $session->load(['day', 'speakers']);
        $speaker = $this->authorizedSpeaker($request, $session);
        $svg = $this->shareCards->speakerSvg($speaker, $session);
        $headers = ['Content-Type' => 'image/svg+xml; charset=UTF-8'];

        if ($request->boolean('download')) {
            $headers['Content-Disposition'] = 'attachment; filename="'.(AppSettings::all()['brand_slug'] ?? 'event-app').'-speaker-card.svg"';
        }

        return response($svg, 200, $headers);
    }

    protected function authorizedSpeaker(Request $request, Session $session)
    {
        $user = $request->user();

        abort_unless(
            $user->is_admin || $session->resolvedSpeakers()->contains(fn ($speaker) => $speaker->id === $user->id),
            403
        );

        return $user->is_admin
            ? $session->resolvedSpeakers()->first() ?? $user
            : $user;
    }
}
