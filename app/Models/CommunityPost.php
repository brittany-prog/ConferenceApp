<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = [
        'topic_id',
        'user_id',
        'parent_id',
        'body',
        'meta',
        'status',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'meta' => 'array',
    ];

    public function topic()
    {
        return $this->belongsTo(CommunityTopic::class, 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function introField(string $key): ?string
    {
        $value = $this->meta[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
