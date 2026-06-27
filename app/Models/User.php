<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'title',
        'organization',
        'location',
        'bio',
        'interests',
        'linkedin_url',
        'website_url',
        'profile_photo_path',
        'password',
        'is_admin',
        'can_login',
        'is_speaker',
        'is_exhibitor',
        'sponsor_id',
        'login_code',
        'login_code_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'login_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'can_login' => 'boolean',
        'is_speaker' => 'boolean',
        'is_exhibitor' => 'boolean',
        'login_code_expires_at' => 'datetime',
    ];

    public function savedSessions()
    {
        return $this->belongsToMany(Session::class, 'saved_sessions')->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function speakingSessions()
    {
        return $this->belongsToMany(Session::class, 'session_speaker')->withTimestamps();
    }

    public function sessionFeedback()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function interestedSponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'sponsor_user_interest')->withTimestamps();
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    public function earnedBadges()
    {
        return $this->hasMany(UserBadge::class)->latest('awarded_at');
    }

    public function pointsLedger()
    {
        return $this->hasMany(UserPointLedger::class, 'user_id')->latest();
    }
}
