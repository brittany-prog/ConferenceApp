<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\CommunityPost;
use App\Models\CommunityTopic;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserPointLedger;
use App\Services\EngagementService;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function index(Request $request)
    {
        $topics = CommunityTopic::query()
            ->where('is_active', true)
            ->withCount(['posts as published_posts_count' => fn ($query) => $query->where('status', 'published')])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $introTopic = $topics->firstWhere('is_intro', true);
        $selectedTopic = $introTopic ?: $topics->first();

        $recentPosts = CommunityPost::query()
            ->with(['user', 'topic'])
            ->where('status', 'published')
            ->whereNull('parent_id')
            ->latest()
            ->take(6)
            ->get();

        $user = $request->user();
        $sparkSummary = $this->engagementService->buildUserSummary($user);
        $badges = Badge::query()->where('is_active', true)->orderBy('name')->get();
        $leaderboardUsers = User::query()
            ->where('can_login', true)
            ->select('users.*')
            ->selectSub(
                UserPointLedger::query()
                    ->selectRaw('COALESCE(SUM(points), 0)')
                    ->whereColumn('user_id', 'users.id'),
                'leaderboard_points'
            )
            ->selectSub(
                UserBadge::query()
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('user_id', 'users.id'),
                'leaderboard_badge_count'
            )
            ->with([
                'earnedBadges' => fn ($query) => $query->with('badge')->latest('awarded_at'),
            ])
            ->orderByDesc('leaderboard_points')
            ->orderByDesc('leaderboard_badge_count')
            ->orderBy('name')
            ->take(10)
            ->get();

        return view('community.index', compact('topics', 'selectedTopic', 'recentPosts', 'sparkSummary', 'badges', 'leaderboardUsers'));
    }

    public function show(Request $request, CommunityTopic $topic)
    {
        abort_unless($topic->is_active || $request->user()->is_admin, 404);

        $topic->load([
            'posts' => function ($query) {
                $query->with([
                    'user',
                    'replies' => fn ($replyQuery) => $replyQuery->where('status', 'published')->with('user')->oldest(),
                ])
                    ->whereNull('parent_id')
                    ->where('status', 'published')
                    ->orderByDesc('is_pinned')
                    ->latest();
            },
        ]);

        $topics = CommunityTopic::query()
            ->where('is_active', true)
            ->withCount(['posts as published_posts_count' => fn ($query) => $query->where('status', 'published')])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $sparkSummary = $this->engagementService->buildUserSummary($request->user());
        $userIntroduction = null;

        if ($topic->is_intro) {
            $userIntroduction = CommunityPost::query()
                ->where('topic_id', $topic->id)
                ->where('user_id', $request->user()->id)
                ->whereNull('parent_id')
                ->first();
        }

        $introFields = [
            'headline' => old('headline', $userIntroduction?->introField('headline')),
            'role_title' => old('role_title', $userIntroduction?->introField('role_title') ?: $request->user()->title),
            'organization' => old('organization', $userIntroduction?->introField('organization') ?: $request->user()->organization),
            'why_here' => old('why_here', $userIntroduction?->introField('why_here')),
            'building' => old('building', $userIntroduction?->introField('building')),
            'meet' => old('meet', $userIntroduction?->introField('meet')),
        ];

        return view('community.show', compact('topic', 'topics', 'sparkSummary', 'userIntroduction', 'introFields'));
    }

    public function store(Request $request, CommunityTopic $topic)
    {
        abort_unless($topic->is_active, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1500'],
            'parent_id' => ['nullable', 'integer', 'exists:community_posts,id'],
            'headline' => ['nullable', 'string', 'max:255'],
            'role_title' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'why_here' => ['nullable', 'string', 'max:500'],
            'building' => ['nullable', 'string', 'max:500'],
            'meet' => ['nullable', 'string', 'max:500'],
        ]);

        $parent = null;

        if (! empty($validated['parent_id'])) {
            $parent = CommunityPost::query()
                ->where('id', $validated['parent_id'])
                ->where('topic_id', $topic->id)
                ->where('status', 'published')
                ->firstOrFail();
        }

        $user = $request->user();

        if ($topic->is_intro && ! $parent) {
            $introMeta = [
                'headline' => trim((string) ($validated['headline'] ?? '')),
                'role_title' => trim((string) ($validated['role_title'] ?? '')),
                'organization' => trim((string) ($validated['organization'] ?? '')),
                'why_here' => trim((string) ($validated['why_here'] ?? '')),
                'building' => trim((string) ($validated['building'] ?? '')),
                'meet' => trim((string) ($validated['meet'] ?? '')),
            ];

            $post = CommunityPost::query()->updateOrCreate(
                [
                    'topic_id' => $topic->id,
                    'user_id' => $user->id,
                    'parent_id' => null,
                ],
                [
                    'body' => trim($validated['body']),
                    'meta' => $introMeta,
                    'status' => 'published',
                ]
            );

            $this->engagementService->awardOnce(
                $user,
                'introduction_posted',
                25,
                'community_topic',
                $topic->id,
                'Introduced yourself to the Southern Spark community.'
            );

            return redirect('/community/topics/'.$topic->slug.'#post-'.$post->id)
                ->with('success', 'Your introduction is live.');
        }

        $post = CommunityPost::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'parent_id' => $parent?->id,
            'body' => trim($validated['body']),
            'status' => 'published',
        ]);

        $this->engagementService->awardCommunityReply($user, $post->id);

        return redirect('/community/topics/'.$topic->slug.'#post-'.$post->id)
            ->with('success', $parent ? 'Reply posted.' : 'Post shared with the community.');
    }
}
