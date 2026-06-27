<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Services\EngagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(private readonly EngagementService $engagementService)
    {
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $speakerShareSession = Session::query()
            ->with(['day', 'track', 'speakers'])
            ->where(function ($query) use ($user) {
                $query->where('speaker_user_id', $user->id)
                    ->orWhereHas('speakers', fn ($nested) => $nested->where('users.id', $user->id));
            })
            ->orderBy('day_id')
            ->orderBy('start_time')
            ->first();

        return view('profile.edit', [
            'user' => $user,
            'speakerShareSession' => $speakerShareSession,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'title' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'interests' => ['nullable', 'string', 'max:500'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($request->user()->profile_photo_path) {
                Storage::disk('public')->delete($request->user()->profile_photo_path);
            }

            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $request->user()->update($validated);
        $this->engagementService->awardProfileComplete($request->user()->fresh());

        return redirect('/profile')->with('success', 'Profile updated successfully.');
    }
}
