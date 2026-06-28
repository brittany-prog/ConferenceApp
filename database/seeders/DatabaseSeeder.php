<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'david-ceo@redbeans.io'],
            [
                'name' => 'David CEO',
                'password' => Hash::make('RedBeans1234!'),
                'is_admin' => true,
                'can_login' => true,
            ]
        );

        $defaults = [
            'brand_name' => env('BRAND_NAME', 'Conference App'),
            'brand_tagline' => env('BRAND_TAGLINE', 'A polished conference app for your event.'),
            'brand_primary_color' => env('BRAND_PRIMARY_COLOR', '#0f7c73'),
            'brand_accent_color' => env('BRAND_ACCENT_COLOR', '#f59e0b'),
            'brand_logo_url' => env('BRAND_LOGO_URL'),
            'homepage_subtitle' => 'Conference Hub',
            'homepage_description' => 'Browse the agenda, explore speakers, follow announcements, and keep attendees connected from one branded experience.',
            'homepage_primary_cta_label' => 'Browse Sessions',
            'homepage_primary_cta_link' => '/sessions',
            'homepage_secondary_cta_label' => 'Meet the Speakers',
            'homepage_secondary_cta_link' => '/speakers',
            'registration_enabled' => env('REGISTRATION_ENABLED', true) ? '1' : '0',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }
    }
}
