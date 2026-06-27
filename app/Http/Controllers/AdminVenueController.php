<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVenueController extends Controller
{
    public function edit()
    {
        return view('admin.venue.edit', [
            'settings' => AppSettings::all(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_page_subtitle' => ['nullable', 'string', 'max:500'],
            'venue_parking_note' => ['required', 'string', 'max:1200'],
            'venue_arrival_note' => ['required', 'string', 'max:1200'],
            'venue_helpful_tip' => ['nullable', 'string', 'max:1200'],
            'venue_arrival_timing_note' => ['nullable', 'string', 'max:1200'],
            'venue_best_use_note' => ['nullable', 'string', 'max:1200'],
            'venue_schedule_note' => ['nullable', 'string', 'max:1200'],
            'venue_image' => ['nullable', 'image', 'max:6144'],
        ]);

        if ($request->hasFile('venue_image')) {
            $existingPath = Setting::query()->where('key', 'venue_image_path')->value('value');

            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }

            $validated['venue_image_path'] = $request->file('venue_image')->store('venue', 'public');
        }

        unset($validated['venue_image']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return redirect('/admin/venue')->with('success', 'Venue page updated successfully.');
    }
}
