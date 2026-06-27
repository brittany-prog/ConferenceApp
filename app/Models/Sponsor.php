<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'name',
        'headline',
        'tier',
        'website_url',
        'booth_location',
        'description',
        'cta_label',
        'cta_url',
        'resource_title',
        'resource_url',
        'logo_path',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'sponsor_user_interest')->withTimestamps();
    }

    public function exhibitors()
    {
        return $this->hasMany(User::class)->where('is_exhibitor', true)->where('can_login', true)->orderBy('name');
    }
}
