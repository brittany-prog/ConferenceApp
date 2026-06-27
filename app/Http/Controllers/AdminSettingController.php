<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => AppSettings::all(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'brand_tagline' => ['nullable', 'string', 'max:255'],
            'brand_primary_color' => ['required', 'string', 'max:20'],
            'brand_accent_color' => ['required', 'string', 'max:20'],
            'brand_logo_url' => ['nullable', 'url', 'max:2048'],
            'brand_logo' => ['nullable', 'image', 'max:4096'],
            'header_image' => ['nullable', 'image', 'max:6144'],
            'homepage_subtitle' => ['nullable', 'string', 'max:255'],
            'homepage_description' => ['nullable', 'string', 'max:2000'],
            'homepage_primary_cta_label' => ['nullable', 'string', 'max:255'],
            'homepage_primary_cta_link' => ['nullable', 'string', 'max:255'],
            'homepage_secondary_cta_label' => ['nullable', 'string', 'max:255'],
            'homepage_secondary_cta_link' => ['nullable', 'string', 'max:255'],
            'public_ticket_label' => ['nullable', 'string', 'max:255'],
            'public_ticket_url' => ['nullable', 'url', 'max:2048'],
            'agenda_preview_label' => ['nullable', 'string', 'max:255'],
            'event_start_date' => ['nullable', 'date'],
            'event_end_date' => ['nullable', 'date'],
            'event_city_region' => ['nullable', 'string', 'max:255'],
            'community_page_title' => ['nullable', 'string', 'max:255'],
            'community_page_description' => ['nullable', 'string', 'max:2000'],
            'community_external_heading' => ['nullable', 'string', 'max:255'],
            'community_external_description' => ['nullable', 'string', 'max:2000'],
            'community_external_url' => ['nullable', 'url', 'max:2048'],
            'community_external_cta_label' => ['nullable', 'string', 'max:255'],
            'login_heading' => ['nullable', 'string', 'max:255'],
            'login_description' => ['nullable', 'string', 'max:2000'],
            'login_admin_note' => ['nullable', 'string', 'max:255'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
            'footer_powered_by_label' => ['nullable', 'string', 'max:255'],
            'footer_powered_by_url' => ['nullable', 'url', 'max:2048'],
            'registration_enabled' => ['nullable', 'boolean'],
            'event_access_code' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['registration_enabled'] = $request->boolean('registration_enabled') ? '1' : '0';

        if ($request->hasFile('brand_logo')) {
            $existingLogoPath = Setting::query()->where('key', 'brand_logo_path')->value('value');

            if ($existingLogoPath) {
                Storage::disk('public')->delete($existingLogoPath);
            }

            $validated['brand_logo_path'] = $request->file('brand_logo')->store('branding', 'public');
        }

        if ($request->hasFile('header_image')) {
            $existingPath = Setting::query()->where('key', 'header_image_path')->value('value')
                ?: Setting::query()->where('key', 'dashboard_cover_image_path')->value('value');

            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }

            $storedPath = $request->file('header_image')->store('branding', 'public');
            $validated['header_image_path'] = $storedPath;
            $validated['dashboard_cover_image_path'] = $storedPath;
        }

        unset($validated['brand_logo'], $validated['header_image']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return redirect('/admin/settings')->with('success', 'Branding and access settings updated.');
    }
}
