<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SpeakerController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'az');
        $direction = $sort === 'za' ? 'desc' : 'asc';
        $searchableColumns = collect([
            'name',
            'organization',
            'title',
            'location',
            'bio',
        ])->filter(fn ($column) => Schema::hasColumn('users', $column))->values();

        $speakers = User::query()
            ->where('is_speaker', true)
            ->where('can_login', true)
            ->when($query !== '' && $searchableColumns->isNotEmpty(), function ($builder) use ($query, $searchableColumns) {
                $builder->where(function ($nested) use ($query, $searchableColumns) {
                    foreach ($searchableColumns as $index => $column) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $nested->{$method}($column, 'like', '%'.$query.'%');
                    }
                });
            })
            ->orderBy('name', $direction)
            ->paginate(12)
            ->withQueryString();

        return view('speakers.index', compact('speakers', 'query', 'sort'));
    }

    public function show(User $speaker)
    {
        abort_unless($speaker->is_speaker && $speaker->can_login, 404);

        $sessions = Session::query()
            ->with(['day', 'track', 'speakers'])
            ->where(function ($query) use ($speaker) {
                $query->where('speaker_user_id', $speaker->id)
                    ->orWhereHas('speakers', fn ($nested) => $nested->where('users.id', $speaker->id));
            })
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->get();

        return view('speakers.show', compact('speaker', 'sessions'));
    }
}
