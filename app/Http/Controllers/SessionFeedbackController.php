<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Services\EngagementService;
use Illuminate\Http\Request;

class SessionFeedbackController extends Controller
{
    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function store(Request $request, Session $session)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1500'],
        ]);

        $session->feedback()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        $this->engagementService->awardOnce(
            $request->user(),
            'session_feedback_submitted',
            15,
            'session',
            $session->id,
            'Shared feedback on a conference session.'
        );

        return back()->with('success', 'Session feedback saved.');
    }
}
