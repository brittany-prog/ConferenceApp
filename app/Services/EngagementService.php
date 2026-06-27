<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\CommunityTopic;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserPointLedger;

class EngagementService
{
    public function awardOnce(
        User $user,
        string $action,
        int $points,
        string $referenceType = '',
        int $referenceId = 0,
        ?string $description = null,
        array $meta = []
    ): bool {
        $existing = UserPointLedger::query()
            ->where('user_id', $user->id)
            ->where('action', $action)
            ->where('reference_type', $referenceType)
            ->where('reference_id', $referenceId)
            ->exists();

        if ($existing) {
            return false;
        }

        UserPointLedger::create([
            'user_id' => $user->id,
            'action' => $action,
            'points' => $points,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'meta' => $meta,
        ]);

        $this->evaluateBadges($user->fresh());

        return true;
    }

    public function awardCommunityReply(User $user, int $postId): bool
    {
        $dailyReplyCount = UserPointLedger::query()
            ->where('user_id', $user->id)
            ->where('action', 'community_reply')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $points = $dailyReplyCount < 3 ? 10 : 0;

        return $this->awardOnce(
            $user,
            'community_reply',
            $points,
            'community_post',
            $postId,
            $points > 0 ? 'Joined a community discussion.' : 'Joined a community discussion after the daily points cap.'
        );
    }

    public function awardProfileComplete(User $user): void
    {
        $completedFields = collect([
            $user->title,
            $user->organization,
            $user->location,
            $user->bio,
            $user->interests,
            $user->linkedin_url,
            $user->website_url,
            $user->profile_photo_path,
        ])->filter(fn ($value) => filled($value))->count();

        if ($completedFields >= 5) {
            $this->awardOnce($user, 'profile_complete', 20, 'profile', $user->id, 'Completed a rich attendee profile.');
        }
    }

    public function evaluateBadges(User $user): void
    {
        $user->loadMissing(['communityPosts', 'savedSessions', 'sessionFeedback', 'interestedSponsors']);

        $badgeRules = [
            'early-spark' => fn () => UserPointLedger::query()
                ->where('user_id', $user->id)
                ->where('action', 'profile_complete')
                ->exists(),
            'introduced-yourself' => fn () => $this->hasCommunityPost($user, true),
            'conversation-starter' => fn () => UserPointLedger::query()
                ->where('user_id', $user->id)
                ->where('action', 'community_reply')
                ->exists(),
            'community-contributor' => fn () => $user->communityPosts()->where('status', 'published')->count() >= 4,
            'agenda-builder' => fn () => $user->savedSessions()->count() >= 3,
            'feedback-friend' => fn () => $user->sessionFeedback()->count() >= 2,
            'sponsor-scout' => fn () => $user->interestedSponsors()->count() >= 2,
            'southern-spark-champion' => fn () => UserPointLedger::query()->where('user_id', $user->id)->sum('points') >= 100,
        ];

        $badges = Badge::query()
            ->whereIn('slug', array_keys($badgeRules))
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        foreach ($badgeRules as $slug => $qualifies) {
            $badge = $badges->get($slug);

            if (! $badge || ! $qualifies()) {
                continue;
            }

            UserBadge::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                ],
                [
                    'awarded_at' => now(),
                ]
            );
        }
    }

    public function buildUserSummary(User $user): array
    {
        $points = UserPointLedger::query()->where('user_id', $user->id)->sum('points');
        $badges = $user->earnedBadges()->with('badge')->latest('awarded_at')->get();
        $communityTopic = CommunityTopic::query()->where('is_intro', true)->first();
        $hasIntroduction = $communityTopic
            ? $user->communityPosts()->where('topic_id', $communityTopic->id)->whereNull('parent_id')->where('status', 'published')->exists()
            : false;

        $availableBadges = Badge::query()->where('is_active', true)->orderBy('id')->get();
        $nextBadge = $availableBadges->first(function (Badge $badge) use ($badges) {
            return ! $badges->contains(fn ($userBadge) => $userBadge->badge_id === $badge->id);
        });
        $earnedBadgeIds = $badges->pluck('badge_id')->all();
        $previewBadges = $availableBadges
            ->sortBy(fn (Badge $badge) => [
                in_array($badge->id, $earnedBadgeIds, true) ? 0 : 1,
                in_array($badge->id, $earnedBadgeIds, true)
                    ? array_search($badge->id, $earnedBadgeIds, true)
                    : $badge->id,
            ])
            ->take(4)
            ->values();

        return [
            'points' => $points,
            'badge_count' => $badges->count(),
            'recent_badge' => optional($badges->first())->badge,
            'next_badge' => $nextBadge,
            'preview_badges' => $previewBadges,
            'has_introduction' => $hasIntroduction,
            'community_posts_count' => $user->communityPosts()->where('status', 'published')->count(),
            'earning_actions' => [
                ['label' => 'Complete your profile', 'points' => 20],
                ['label' => 'Introduce yourself', 'points' => 25],
                ['label' => 'Reply to a topic', 'points' => 10],
                ['label' => 'Leave session feedback', 'points' => 15],
                ['label' => 'Save a sponsor', 'points' => 10],
            ],
        ];
    }

    private function hasCommunityPost(User $user, bool $introOnly): bool
    {
        $query = $user->communityPosts()->where('status', 'published');

        if ($introOnly) {
            $query->whereHas('topic', fn ($topicQuery) => $topicQuery->where('is_intro', true));
        }

        return $query->exists();
    }
}
