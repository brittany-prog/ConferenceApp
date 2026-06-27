<?php

namespace App\Support;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AppSettings
{
    public static function all(): array
    {
        $defaults = [
            'brand_name' => env('BRAND_NAME', env('APP_NAME', 'Conference App')),
            'brand_tagline' => env('BRAND_TAGLINE', 'A polished conference app for your event.'),
            'brand_primary_color' => env('BRAND_PRIMARY_COLOR', '#0f7c73'),
            'brand_accent_color' => env('BRAND_ACCENT_COLOR', '#f59e0b'),
            'brand_logo_url' => env('BRAND_LOGO_URL'),
            'brand_logo_path' => env('BRAND_LOGO_PATH'),
            'header_image_path' => env('HEADER_IMAGE_PATH', env('DASHBOARD_COVER_IMAGE_PATH')),
            'dashboard_cover_image_path' => env('DASHBOARD_COVER_IMAGE_PATH'),
            'homepage_subtitle' => 'Conference Hub',
            'homepage_description' => 'Browse the agenda, explore speakers, follow announcements, and keep attendees connected from one branded experience.',
            'homepage_primary_cta_label' => 'Browse Sessions',
            'homepage_primary_cta_link' => '/sessions',
            'homepage_secondary_cta_label' => 'Meet the Speakers',
            'homepage_secondary_cta_link' => '/speakers',
            'public_ticket_label' => env('PUBLIC_TICKET_LABEL', 'Get Tickets'),
            'public_ticket_url' => env('PUBLIC_TICKET_URL'),
            'agenda_preview_label' => env('AGENDA_PREVIEW_LABEL', 'Preview the agenda'),
            'event_start_date' => env('EVENT_START_DATE'),
            'event_end_date' => env('EVENT_END_DATE'),
            'event_city_region' => env('EVENT_CITY_REGION'),
            'community_page_title' => 'Meet people, join discussions, and build momentum.',
            'community_page_description' => 'This space is intentionally structured: start with Introduce Yourself, then jump into guided discussion topics that keep the conversation useful and welcoming.',
            'community_external_heading' => env('COMMUNITY_EXTERNAL_HEADING', 'Keep the conversation going'),
            'community_external_description' => env('COMMUNITY_EXTERNAL_DESCRIPTION', 'Share updates, post resources, and stay connected after the event ends.'),
            'community_external_url' => env('COMMUNITY_EXTERNAL_URL'),
            'community_external_cta_label' => env('COMMUNITY_EXTERNAL_CTA_LABEL', 'Open Community Space'),
            'venue_name' => 'Main Venue',
            'venue_page_subtitle' => 'Find the building, plan your arrival, and know where to park before you head to the event.',
            'venue_parking_note' => 'Add your parking instructions here.',
            'venue_arrival_note' => 'Add a quick arrival note so attendees know what to expect when they get on site.',
            'venue_helpful_tip' => 'Use this space for a practical venue tip, meetup note, or accessibility reminder.',
            'venue_arrival_timing_note' => 'Arrive a little early if you want extra time to park and get oriented before your first session.',
            'venue_best_use_note' => 'Use this page as the quick day-of reference for where to go and where to leave your car.',
            'venue_schedule_note' => 'Open the event schedule to plan where you need to be once you arrive.',
            'venue_image_path' => null,
            'login_heading' => 'Sign in to your event app',
            'login_description' => 'Use your attendee or admin account to continue. Attendees confirm access with a one-time email code after their password is verified.',
            'login_admin_note' => 'Admins sign in directly. Attendees will confirm with their email code.',
            'footer_copyright' => env('FOOTER_COPYRIGHT'),
            'footer_powered_by_label' => env('FOOTER_POWERED_BY_LABEL', 'App powered by Red Beans'),
            'footer_powered_by_url' => env('FOOTER_POWERED_BY_URL', 'https://redbeansgroup.com'),
            'registration_enabled' => filter_var(env('REGISTRATION_ENABLED', true), FILTER_VALIDATE_BOOL),
            'event_access_code' => env('EVENT_ACCESS_CODE'),
        ];

        try {
            $stored = Setting::query()->pluck('value', 'key')->all();
        } catch (\Throwable) {
            $stored = [];
        }

        $settings = array_merge($defaults, Arr::only($stored, array_keys($defaults)));
        $settings['registration_enabled'] = filter_var($settings['registration_enabled'], FILTER_VALIDATE_BOOL);
        $settings['brand_slug'] = Str::slug((string) ($settings['brand_name'] ?? 'event-app')) ?: 'event-app';
        $settings['theme_storage_key'] = $settings['brand_slug'].'-theme';
        $settings['event_date_range_label'] = self::formatEventDateRange(
            $settings['event_start_date'] ?? null,
            $settings['event_end_date'] ?? null
        );
        $settings['brand_logo_asset'] = ! empty($settings['brand_logo_path'])
            ? asset('storage/'.$settings['brand_logo_path'])
            : ($settings['brand_logo_url'] ?? null);
        $settings['favicon_asset'] = $settings['brand_logo_asset'] ?: asset('favicon-default.svg');
        $settings['has_public_ticket_link'] = filled($settings['public_ticket_url'] ?? null);
        $settings['has_external_community_link'] = filled($settings['community_external_url'] ?? null);

        return $settings;
    }

    public static function formatEventDateRange(?string $startDate, ?string $endDate): ?string
    {
        if (! $startDate && ! $endDate) {
            return null;
        }

        try {
            $start = $startDate ? Carbon::parse($startDate) : null;
            $end = $endDate ? Carbon::parse($endDate) : null;
        } catch (\Throwable) {
            return null;
        }

        if ($start && $end) {
            if ($start->isSameDay($end)) {
                return $start->format('F j, Y');
            }

            if ($start->isSameMonth($end) && $start->isSameYear($end)) {
                return $start->format('F j').' - '.$end->format('j, Y');
            }

            if ($start->isSameYear($end)) {
                return $start->format('F j').' - '.$end->format('F j, Y');
            }

            return $start->format('F j, Y').' - '.$end->format('F j, Y');
        }

        return ($start ?? $end)?->format('F j, Y');
    }
}
