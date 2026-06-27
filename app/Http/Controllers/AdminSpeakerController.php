<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSpeakerController extends Controller
{
    public function index()
    {
        $speakers = User::query()
            ->where('is_speaker', true)
            ->orderBy('name')
            ->get();

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create()
    {
        return redirect('/admin/users')->with('success', 'Assign speaker profiles from the users screen.');
    }

    public function store(Request $request)
    {
        return redirect('/admin/users');
    }

    public function edit(User $speaker)
    {
        abort_unless($speaker->is_speaker, 404);

        return view('admin.speakers.edit', compact('speaker'));
    }

    public function update(Request $request, User $speaker)
    {
        abort_unless($speaker->is_speaker, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($speaker->profile_photo_path) {
                Storage::disk('public')->delete($speaker->profile_photo_path);
            }

            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $speaker->update($validated);

        return redirect('/admin/speakers')->with('success', 'Speaker updated successfully.');
    }

    public function destroy(User $speaker)
    {
        abort_unless($speaker->is_speaker, 404);

        $speaker->update(['is_speaker' => false]);

        return redirect('/admin/speakers')->with('success', 'Speaker designation removed successfully.');
    }
}
