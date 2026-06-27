<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AttendeeController extends Controller
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
            'interests',
        ])->filter(fn ($column) => Schema::hasColumn('users', $column))->values();

        $attendees = User::query()
            ->where('can_login', true)
            ->where('is_admin', false)
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

        return view('attendees.index', [
            'attendees' => $attendees,
            'query' => $query,
            'sort' => $sort,
        ]);
    }
}
