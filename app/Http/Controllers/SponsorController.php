<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Services\EngagementService;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function index()
    {
        $sponsors = Sponsor::query()
            ->where('is_active', true)
            ->with(['exhibitors'])
            ->withCount('exhibitors')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('sponsors.index', compact('sponsors'));
    }

    public function show(Sponsor $sponsor)
    {
        abort_unless($sponsor->is_active, 404);

        $sponsor->loadCount(['interestedUsers', 'exhibitors'])->load('exhibitors');

        $isInterested = $sponsor->interestedUsers()
            ->where('user_id', auth()->id())
            ->exists();

        return view('sponsors.show', compact('sponsor', 'isInterested'));
    }

    public function toggleInterest(Request $request, Sponsor $sponsor)
    {
        abort_unless($sponsor->is_active, 404);

        $user = $request->user();

        if ($user->interestedSponsors()->where('sponsor_id', $sponsor->id)->exists()) {
            $user->interestedSponsors()->detach($sponsor->id);

            return back()->with('success', 'Sponsor removed from your interested list.');
        }

        $user->interestedSponsors()->attach($sponsor->id);
        $this->engagementService->awardOnce(
            $user,
            'sponsor_interest_marked',
            10,
            'sponsor',
            $sponsor->id,
            'Saved a sponsor for follow-up.'
        );

        return back()->with('success', 'Sponsor saved to your interested list.');
    }
}
