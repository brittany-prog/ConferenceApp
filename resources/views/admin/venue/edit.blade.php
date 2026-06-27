@extends('layouts.app')

@section('title', 'Edit Venue | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <span class="eyebrow">Admin</span>
            <h2 style="margin: 10px 0 0;">Edit venue page</h2>
            <p class="lede" style="margin:0;">Update the venue details attendees see on the public-facing venue page.</p>
        </div>

        <form method="POST" action="/admin/venue" enctype="multipart/form-data" class="stack">
            @csrf
            @method('PUT')

            <div class="form-section stack">
                <div class="grid grid-2">
                    <div>
                        <label for="venue_name">Venue name</label>
                        <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name', $settings['venue_name']) }}" required>
                    </div>
                    <div>
                        <label for="venue_image">Venue hero image</label>
                        <input id="venue_image" type="file" name="venue_image" accept="image/*">
                    </div>
                </div>

                @if (!empty($settings['venue_image_path']))
                    <div>
                        <p class="subtle" style="margin:0 0 10px;">Current venue image</p>
                        <img src="{{ asset('storage/'.$settings['venue_image_path']) }}" alt="Venue image preview" style="width:100%; max-height:260px; object-fit:cover; border-radius:20px; border:1px solid var(--brand-line);">
                    </div>
                @endif

                <label for="venue_page_subtitle">Page subtitle</label>
                <textarea id="venue_page_subtitle" name="venue_page_subtitle" rows="3">{{ old('venue_page_subtitle', $settings['venue_page_subtitle']) }}</textarea>
            </div>

            <div class="form-section stack">
                <div class="grid grid-2">
                    <div>
                        <label for="venue_arrival_note">Arrival note</label>
                        <textarea id="venue_arrival_note" name="venue_arrival_note" rows="4" required>{{ old('venue_arrival_note', $settings['venue_arrival_note']) }}</textarea>
                    </div>
                    <div>
                        <label for="venue_parking_note">Parking note</label>
                        <textarea id="venue_parking_note" name="venue_parking_note" rows="4" required>{{ old('venue_parking_note', $settings['venue_parking_note']) }}</textarea>
                    </div>
                </div>

                <label for="venue_helpful_tip">Helpful tip</label>
                <textarea id="venue_helpful_tip" name="venue_helpful_tip" rows="3">{{ old('venue_helpful_tip', $settings['venue_helpful_tip']) }}</textarea>

                <div class="grid grid-3">
                    <div>
                        <label for="venue_arrival_timing_note">Arrival timing card</label>
                        <textarea id="venue_arrival_timing_note" name="venue_arrival_timing_note" rows="4">{{ old('venue_arrival_timing_note', $settings['venue_arrival_timing_note']) }}</textarea>
                    </div>
                    <div>
                        <label for="venue_best_use_note">Best use card</label>
                        <textarea id="venue_best_use_note" name="venue_best_use_note" rows="4">{{ old('venue_best_use_note', $settings['venue_best_use_note']) }}</textarea>
                    </div>
                    <div>
                        <label for="venue_schedule_note">Agenda card</label>
                        <textarea id="venue_schedule_note" name="venue_schedule_note" rows="4">{{ old('venue_schedule_note', $settings['venue_schedule_note']) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="action-row">
                <a href="/venue">View venue page</a>
                <button type="submit" class="button">Save venue page</button>
            </div>
        </form>
    </section>
@endsection
