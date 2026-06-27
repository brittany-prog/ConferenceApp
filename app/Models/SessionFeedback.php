<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionFeedback extends Model
{
    protected $table = 'session_feedback';

    protected $fillable = [
        'session_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
