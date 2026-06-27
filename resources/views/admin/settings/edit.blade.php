@extends('layouts.app')

@section('title', 'Brand Settings | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <span class="eyebrow">White Label</span>
            <h2 style="margin: 10px 0 0;">Branding and access settings</h2>
            <p class="lede" style="margin:0;">These values drive the attendee-facing header, homepage hero, and registration availability.</p>
        </div>

        <form method="POST" action="/admin/settings" enctype="multipart/form-data" class="stack">
            @csrf
            @method('PUT')

            <div class="form-section stack">
                <div class="grid grid-2">
                    <div>
                        <label for="brand_name">Brand name</label>
                        <input id="brand_name" type="text" name="brand_name" value="{{ old('brand_name', $settings['brand_name']) }}" required>
                    </div>
                    <div>
                        <label for="brand_tagline">Tagline</label>
                        <input id="brand_tagline" type="text" name="brand_tagline" value="{{ old('brand_tagline', $settings['brand_tagline']) }}">
                    </div>
                    <div>
                        <label for="brand_primary_color">Primary color</label>
                        <input id="brand_primary_color" type="text" name="brand_primary_color" value="{{ old('brand_primary_color', $settings['brand_primary_color']) }}" required>
                    </div>
                    <div>
                        <label for="brand_accent_color">Accent color</label>
                        <input id="brand_accent_color" type="text" name="brand_accent_color" value="{{ old('brand_accent_color', $settings['brand_accent_color']) }}" required>
                    </div>
                </div>

                <div class="grid grid-2" style="align-items:start;">
                    <div>
                        <label for="brand_logo">Logo upload</label>
                        <input id="brand_logo" type="file" name="brand_logo" accept="image/*">
                        <p class="field-help">Use a square or near-square logo for the cleanest result in the nav, landing page, and auto-generated favicon.</p>
                    </div>
                    <div>
                        @php($brandLogoPath = $settings['brand_logo_path'] ?? null)
                        @if (!empty($brandLogoPath))
                            <p class="subtle" style="margin:0 0 10px;">Current uploaded logo</p>
                            <img src="{{ asset('storage/'.$brandLogoPath) }}" alt="Brand logo preview" style="width:96px; height:96px; object-fit:cover; border-radius:24px; border:1px solid var(--brand-line); background:#fff;">
                        @else
                            <div class="card">
                                <p class="muted" style="margin:0;">No uploaded logo yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <label for="brand_logo_url">Logo URL fallback</label>
                <input id="brand_logo_url" type="url" name="brand_logo_url" value="{{ old('brand_logo_url', $settings['brand_logo_url']) }}">
                <p class="field-help">If you upload a logo, the app will use that first. The URL stays as a fallback option.</p>

                <div class="grid grid-2" style="align-items:start;">
                    <div>
                        <label for="header_image">Header image</label>
                        <input id="header_image" type="file" name="header_image" accept="image/*">
                        <p class="field-help">Use a wide landscape image so the top header band stays legible across devices.</p>
                    </div>
                    <div>
                        @php($headerImagePath = $settings['header_image_path'] ?? $settings['dashboard_cover_image_path'] ?? null)
                        @if (!empty($headerImagePath))
                            <p class="subtle" style="margin:0 0 10px;">Current header preview</p>
                            <img src="{{ asset('storage/'.$headerImagePath) }}" alt="Header preview" style="width:100%; max-height:200px; object-fit:cover; border-radius:18px; border:1px solid var(--brand-line);">
                        @else
                            <div class="card">
                                <p class="muted" style="margin:0;">No header image uploaded yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-section stack">
                <div class="grid grid-2">
                    <div>
                        <label for="homepage_subtitle">Homepage subtitle</label>
                        <input id="homepage_subtitle" type="text" name="homepage_subtitle" value="{{ old('homepage_subtitle', $settings['homepage_subtitle']) }}">
                    </div>
                    <div>
                        <label for="homepage_primary_cta_label">Primary CTA label</label>
                        <input id="homepage_primary_cta_label" type="text" name="homepage_primary_cta_label" value="{{ old('homepage_primary_cta_label', $settings['homepage_primary_cta_label']) }}">
                    </div>
                    <div>
                        <label for="homepage_primary_cta_link">Primary CTA link</label>
                        <input id="homepage_primary_cta_link" type="text" name="homepage_primary_cta_link" value="{{ old('homepage_primary_cta_link', $settings['homepage_primary_cta_link']) }}">
                    </div>
                    <div>
                        <label for="homepage_secondary_cta_label">Secondary CTA label</label>
                        <input id="homepage_secondary_cta_label" type="text" name="homepage_secondary_cta_label" value="{{ old('homepage_secondary_cta_label', $settings['homepage_secondary_cta_label']) }}">
                    </div>
                    <div>
                        <label for="homepage_secondary_cta_link">Secondary CTA link</label>
                        <input id="homepage_secondary_cta_link" type="text" name="homepage_secondary_cta_link" value="{{ old('homepage_secondary_cta_link', $settings['homepage_secondary_cta_link']) }}">
                    </div>
                    <div>
                        <label for="public_ticket_label">Public ticket CTA label</label>
                        <input id="public_ticket_label" type="text" name="public_ticket_label" value="{{ old('public_ticket_label', $settings['public_ticket_label']) }}">
                    </div>
                    <div>
                        <label for="public_ticket_url">Public ticket URL</label>
                        <input id="public_ticket_url" type="url" name="public_ticket_url" value="{{ old('public_ticket_url', $settings['public_ticket_url']) }}">
                    </div>
                    <div>
                        <label for="agenda_preview_label">Agenda preview link label</label>
                        <input id="agenda_preview_label" type="text" name="agenda_preview_label" value="{{ old('agenda_preview_label', $settings['agenda_preview_label']) }}">
                    </div>
                    <div>
                        <label for="event_city_region">Event city or region</label>
                        <input id="event_city_region" type="text" name="event_city_region" value="{{ old('event_city_region', $settings['event_city_region']) }}" placeholder="Chicago, IL">
                    </div>
                    <div>
                        <label for="event_start_date">Event start date</label>
                        <input id="event_start_date" type="date" name="event_start_date" value="{{ old('event_start_date', $settings['event_start_date']) }}">
                    </div>
                    <div>
                        <label for="event_end_date">Event end date</label>
                        <input id="event_end_date" type="date" name="event_end_date" value="{{ old('event_end_date', $settings['event_end_date']) }}">
                    </div>
                </div>

                <label for="homepage_description">Homepage description</label>
                <textarea id="homepage_description" name="homepage_description" rows="5">{{ old('homepage_description', $settings['homepage_description']) }}</textarea>

                <div class="grid grid-2">
                    <div>
                        <label for="community_page_title">Community page title</label>
                        <input id="community_page_title" type="text" name="community_page_title" value="{{ old('community_page_title', $settings['community_page_title']) }}">
                    </div>
                </div>

                <label for="community_page_description">Community page description</label>
                <textarea id="community_page_description" name="community_page_description" rows="4">{{ old('community_page_description', $settings['community_page_description']) }}</textarea>

                <div class="grid grid-2">
                    <div>
                        <label for="community_external_heading">External community heading</label>
                        <input id="community_external_heading" type="text" name="community_external_heading" value="{{ old('community_external_heading', $settings['community_external_heading']) }}">
                    </div>
                    <div>
                        <label for="community_external_cta_label">External community CTA label</label>
                        <input id="community_external_cta_label" type="text" name="community_external_cta_label" value="{{ old('community_external_cta_label', $settings['community_external_cta_label']) }}">
                    </div>
                </div>

                <label for="community_external_description">External community description</label>
                <textarea id="community_external_description" name="community_external_description" rows="4">{{ old('community_external_description', $settings['community_external_description']) }}</textarea>

                <label for="community_external_url">External community URL</label>
                <input id="community_external_url" type="url" name="community_external_url" value="{{ old('community_external_url', $settings['community_external_url']) }}">

                <div class="grid grid-2">
                    <div>
                        <label for="login_heading">Login heading</label>
                        <input id="login_heading" type="text" name="login_heading" value="{{ old('login_heading', $settings['login_heading']) }}">
                    </div>
                    <div>
                        <label for="login_admin_note">Login footnote</label>
                        <input id="login_admin_note" type="text" name="login_admin_note" value="{{ old('login_admin_note', $settings['login_admin_note']) }}">
                    </div>
                </div>

                <label for="login_description">Login description</label>
                <textarea id="login_description" name="login_description" rows="4">{{ old('login_description', $settings['login_description']) }}</textarea>

                <div class="grid grid-2">
                    <div>
                        <label for="footer_copyright">Footer copyright</label>
                        <input id="footer_copyright" type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright']) }}" placeholder="Acme Events 2026">
                    </div>
                    <div>
                        <label for="footer_powered_by_label">Footer powered-by label</label>
                        <input id="footer_powered_by_label" type="text" name="footer_powered_by_label" value="{{ old('footer_powered_by_label', $settings['footer_powered_by_label']) }}">
                    </div>
                </div>

                <label for="footer_powered_by_url">Footer powered-by URL</label>
                <input id="footer_powered_by_url" type="url" name="footer_powered_by_url" value="{{ old('footer_powered_by_url', $settings['footer_powered_by_url']) }}">

                <label class="check-row">
                    <input type="checkbox" name="registration_enabled" value="1" @checked(old('registration_enabled', $settings['registration_enabled']))>
                    <span>Allow self-registration for new users</span>
                </label>
                <label for="event_access_code">Event access code</label>
                <input id="event_access_code" type="text" name="event_access_code" value="{{ old('event_access_code', $settings['event_access_code']) }}">
                <p class="field-help">Attendees must enter this code during registration. Existing users can keep signing in normally.</p>
            </div>

            <div class="action-row">
                <p class="subtle" style="margin:0;">These changes affect the public-facing experience immediately after deployment.</p>
                <button type="submit" class="button">Save settings</button>
            </div>
        </form>
    </section>
@endsection
