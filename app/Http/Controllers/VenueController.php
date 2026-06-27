<?php

namespace App\Http\Controllers;

use App\Support\AppSettings;

class VenueController extends Controller
{
    public function __invoke()
    {
        $settings = AppSettings::all();

        return view('venue.show', [
            'venue' => [
                'name' => $settings['venue_name'],
                'subtitle' => $settings['venue_page_subtitle'],
                'parking_note' => $settings['venue_parking_note'],
                'arrival_note' => $settings['venue_arrival_note'],
                'helpful_tip' => $settings['venue_helpful_tip'],
                'arrival_timing_note' => $settings['venue_arrival_timing_note'],
                'best_use_note' => $settings['venue_best_use_note'],
                'schedule_note' => $settings['venue_schedule_note'],
                'image_path' => $settings['venue_image_path'],
            ],
        ]);
    }
}
