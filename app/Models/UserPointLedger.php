<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPointLedger extends Model
{
    protected $table = 'user_points_ledger';

    protected $fillable = [
        'user_id',
        'action',
        'points',
        'reference_type',
        'reference_id',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
