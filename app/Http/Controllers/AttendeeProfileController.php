<?php

namespace App\Http\Controllers;

use App\Models\User;

class AttendeeProfileController extends Controller
{
    public function show(User $user)
    {
        abort_unless($user->can_login && ! $user->is_admin, 404);

        return view('attendees.show', [
            'attendee' => $user,
        ]);
    }
}
