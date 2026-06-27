@extends('layouts.app')

@section('title', 'Profile | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 840px; margin: 0 auto;">
        <div class="grid grid-2" style="align-items:start;">
            <div class="card" style="text-align:center;">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }} profile photo" class="profile-hero-avatar">
                @else
                    <div class="profile-hero-avatar profile-hero-avatar--fallback">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <span class="eyebrow">My Profile</span>
                <h2 style="margin: 12px 0 8px;">{{ $user->name }}</h2>
                <p class="muted" style="margin:0;">{{ $user->title }} @if($user->title && $user->organization) at @endif {{ $user->organization }}</p>
                @if ($user->location)
                    <p class="muted" style="margin:10px 0 0;">{{ $user->location }}</p>
                @endif
                <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap; margin-top:14px;">
                    @if ($user->is_speaker)
                        <span class="eyebrow">Speaker</span>
                    @endif
                    @if ($user->is_admin)
                        <span class="eyebrow">Admin</span>
                    @endif
                </div>
                @if ($user->interests)
                    <p class="muted" style="margin:14px 0 0;"><strong>Interests:</strong> {{ $user->interests }}</p>
                @endif
                <p class="muted" style="margin:14px 0 0;">{{ $user->bio ?: 'Customize your profile with a short bio and photo.' }}</p>
                <div style="margin-top: 18px;">
                    @if ($speakerShareSession)
                        <a href="/share/sessions/{{ $speakerShareSession->id }}/speaker" class="button">Download a Graphic to Promote Your Session</a>
                    @elseif ($user->is_speaker || $user->is_admin)
                        <div class="detail-link-actions">
                            <a href="/share/speaking" class="button">Open Your Speaker Graphics</a>
                            <a href="/share/speaking" class="button secondary">Download Your Social Graphic</a>
                        </div>
                    @else
                        <a href="/share/attending" class="button">Download & Share Your Social Graphic</a>
                    @endif
                </div>
            </div>

            <div class="stack">
                <span class="eyebrow">Edit Details</span>
                <h2 style="margin: 10px 0 0;">Update profile</h2>
                <p class="lede" style="margin:0;">Keep your attendee profile current so others can recognize and connect with you.</p>
            </div>
        </div>

        <form method="POST" action="/profile" enctype="multipart/form-data" class="stack">
            @csrf
            @method('PUT')

            <div class="form-section stack">
                <div class="grid grid-2">
                    <div>
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div>
                        <label for="title">Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $user->title) }}">
                    </div>
                    <div>
                        <label for="organization">Organization</label>
                        <input id="organization" type="text" name="organization" value="{{ old('organization', $user->organization) }}">
                    </div>
                    <div>
                        <label for="location">Location</label>
                        <input id="location" type="text" name="location" value="{{ old('location', $user->location) }}" placeholder="Jackson, MS">
                    </div>
                </div>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="6">{{ old('bio', $user->bio) }}</textarea>

                <label for="interests">Interests</label>
                <input id="interests" type="text" name="interests" value="{{ old('interests', $user->interests) }}" placeholder="AI education, startups, product, civic tech">

                <div class="grid grid-2">
                    <div>
                        <label for="linkedin_url">LinkedIn URL</label>
                        <input id="linkedin_url" type="url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" placeholder="https://www.linkedin.com/in/your-name">
                    </div>
                    <div>
                        <label for="website_url">Website URL</label>
                        <input id="website_url" type="url" name="website_url" value="{{ old('website_url', $user->website_url) }}" placeholder="https://yourwebsite.com">
                    </div>
                </div>

                <div class="profile-photo-uploader">
                    <div class="profile-photo-uploader-preview">
                        @if ($user->profile_photo_path)
                            <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }} profile photo preview">
                        @else
                            <div class="profile-photo-uploader-fallback">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-photo-uploader-copy">
                        <label for="profile_photo">Profile photo</label>
                        <p class="field-help" style="margin-top:0;">Upload a clear headshot so other attendees can recognize you more easily in the directory and messages.</p>
                        <label for="profile_photo" class="button secondary profile-photo-trigger">Choose image</label>
                        <input id="profile_photo" class="profile-photo-input" type="file" name="profile_photo" accept="image/*">
                        <p class="subtle profile-photo-note" style="margin:10px 0 0;">JPG, PNG, or WEBP up to 4MB.</p>
                    </div>
                </div>
            </div>

            <div class="action-row">
                <p class="subtle" style="margin:0;">Your profile becomes visible in the attendee directory after you save.</p>
                <button type="submit" class="button">Save profile</button>
            </div>
        </form>
    </section>
@endsection

@push('page-styles')
    <style>
        .profile-hero-avatar {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 36px;
            margin: 0 auto 16px;
            display: block;
            box-shadow: 0 10px 22px rgba(17, 23, 48, 0.14);
        }

        .profile-hero-avatar--fallback {
            display: grid;
            place-items: center;
            background: var(--button-gradient);
            color: #fff;
            font-size: 2.5rem;
            font-weight: 800;
            box-shadow: 0 10px 22px rgba(17, 23, 48, 0.14);
        }

        .profile-photo-uploader {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 18px;
            align-items: center;
            padding: 18px;
            border-radius: 22px;
            border: 1px dashed var(--brand-line);
            background: var(--surface-soft-bg);
        }

        .profile-photo-uploader-preview img,
        .profile-photo-uploader-fallback {
            width: 104px;
            height: 104px;
            border-radius: 28px;
            object-fit: cover;
            display: block;
            box-shadow: 0 8px 18px rgba(17, 23, 48, 0.12);
        }

        .profile-photo-uploader-fallback {
            display: grid;
            place-items: center;
            background: var(--button-gradient);
            color: #fff;
            font-size: 2rem;
            font-weight: 800;
        }

        .profile-photo-uploader-copy label {
            margin-bottom: 8px;
        }

        .profile-photo-trigger {
            margin-top: 10px;
        }

        .profile-photo-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (max-width: 720px) {
            .profile-photo-uploader {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .profile-photo-uploader-preview img,
            .profile-photo-uploader-fallback {
                margin: 0 auto;
            }

            .profile-photo-trigger {
                width: 100%;
            }
        }
    </style>
@endpush
