@php
    $size = $size ?? 'mini';
    $earned = $earned ?? false;
    $badgeAssetMap = [
        'early-spark' => 'badges/early-spark.png',
        'introduced-yourself' => 'badges/introduced-yourself.png',
        'conversation-starter' => 'badges/conversation-starter.png',
        'community-contributor' => 'badges/community-contributor.png',
        'agenda-builder' => 'badges/agenda-builder.png',
        'feedback-friend' => 'badges/feedback-friend.png',
        'sponsor-scout' => 'badges/sponsor-scout.png',
        'southern-spark-champion' => 'badges/southern-spark-champion.png',
    ];
    $badgeAsset = asset($badgeAssetMap[$badge->slug] ?? 'badges/early-spark.png');
@endphp

<div class="{{ $size === 'full' ? 'badge-art badge-art--full' : 'badge-art badge-art--mini' }} {{ $earned ? 'is-earned' : 'is-locked' }}" title="{{ $badge->name }}">
    <img src="{{ $badgeAsset }}" alt="{{ $badge->name }} badge" loading="lazy">
</div>
