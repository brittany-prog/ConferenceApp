<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityTopic extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'prompt',
        'type',
        'is_active',
        'is_intro',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_intro' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->hasMany(CommunityPost::class, 'topic_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
