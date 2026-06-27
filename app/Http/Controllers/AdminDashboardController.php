<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Session;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'adminsCount' => User::where('is_admin', true)->count(),
            'disabledUsersCount' => User::where('can_login', false)->count(),
            'sessionsCount' => Session::count(),
            'speakersCount' => User::where('is_speaker', true)->count(),
            'announcementsCount' => Announcement::count(),
        ]);
    }
}
