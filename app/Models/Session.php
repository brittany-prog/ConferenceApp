<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Session extends Model
{
    use HasFactory;

    public const AGENDA_TRACK_CATALOG = [
        'featured-keynotes' => [
            'slug' => 'featured-keynotes',
            'name' => 'Featured & Keynotes',
            'style' => ['bg' => '#FFE7EC', 'border' => '#FFB5C0', 'ink' => '#8A2844', 'dot' => '#FF5B73'],
        ],
        'education-workforce' => [
            'slug' => 'education-workforce',
            'name' => 'Education & Workforce',
            'style' => ['bg' => '#E8F8E3', 'border' => '#B5E19F', 'ink' => '#3E6B1B', 'dot' => '#7DCB51'],
        ],
        'ai-tools-build' => [
            'slug' => 'ai-tools-build',
            'name' => 'AI Tools & Build',
            'style' => ['bg' => '#E8EEFF', 'border' => '#B9C7F0', 'ink' => '#213A70', 'dot' => '#4E74C9'],
        ],
        'business-entrepreneurship' => [
            'slug' => 'business-entrepreneurship',
            'name' => 'Business & Entrepreneurship',
            'style' => ['bg' => '#FFF0D8', 'border' => '#F2C98D', 'ink' => '#85561E', 'dot' => '#E5A53B'],
        ],
        'community-policy' => [
            'slug' => 'community-policy',
            'name' => 'Community & Policy',
            'style' => ['bg' => '#F0E9FF', 'border' => '#D0B7F3', 'ink' => '#5B3A86', 'dot' => '#9463D6'],
        ],
        'networking-experience' => [
            'slug' => 'networking-experience',
            'name' => 'Networking & Experience',
            'style' => ['bg' => '#E2F2EC', 'border' => '#A9D4C6', 'ink' => '#1F5B55', 'dot' => '#79BDAA'],
        ],
        'research-innovation' => [
            'slug' => 'research-innovation',
            'name' => 'Research & Innovation',
            'style' => ['bg' => '#EAF1F6', 'border' => '#CAD7E4', 'ink' => '#28445E', 'dot' => '#567D9F'],
        ],
    ];

    protected $fillable = [
        'day_id',
        'track_id',
        'speaker_id',
        'speaker_user_id',
        'title',
        'description',
        'session_type',
        'agenda_track_type',
        'start_time',
        'end_time',
        'location',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function speaker()
    {
        return $this->belongsTo(User::class, 'speaker_user_id');
    }

    public function speakers()
    {
        return $this->belongsToMany(User::class, 'session_speaker')->withTimestamps();
    }

    public function legacySpeaker()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'saved_sessions')->withTimestamps();
    }

    public function feedback()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function resolvedSpeakers(): Collection
    {
        if ($this->relationLoaded('speakers') && $this->speakers->isNotEmpty()) {
            return $this->speakers;
        }

        if ($this->speaker) {
            return collect([$this->speaker]);
        }

        return collect();
    }

    public static function agendaTrackOptions(): Collection
    {
        return collect(self::AGENDA_TRACK_CATALOG)->values();
    }

    public static function agendaTrackSlugs(): array
    {
        return array_keys(self::AGENDA_TRACK_CATALOG);
    }

    public static function agendaTrackForSlug(?string $slug): ?array
    {
        if (! $slug) {
            return null;
        }

        return self::AGENDA_TRACK_CATALOG[$slug] ?? null;
    }
}
