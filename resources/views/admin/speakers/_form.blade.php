@csrf

<div class="form-section stack">
    <div>
        <label for="name">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $speaker->name ?? '') }}" required>
    </div>

    <div class="grid grid-2">
        <div>
            <label for="title">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $speaker->title ?? '') }}">
        </div>
        <div>
            <label for="organization">Organization</label>
            <input id="organization" type="text" name="organization" value="{{ old('organization', $speaker->organization ?? '') }}">
        </div>
    </div>

    <div>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="6">{{ old('bio', $speaker->bio ?? '') }}</textarea>
    </div>

    <div>
        <label for="profile_photo">Headshot</label>
        <input id="profile_photo" type="file" name="profile_photo" accept="image/*">
        @if (!empty($speaker->profile_photo_path))
            <div style="margin-top: 12px;">
                <img src="{{ asset('storage/'.$speaker->profile_photo_path) }}" alt="{{ $speaker->name }} headshot" style="width:120px; height:120px; object-fit:cover; border-radius:24px; box-shadow:0 8px 18px rgba(17, 23, 48, 0.12);">
            </div>
        @endif
    </div>
</div>

<div class="action-row">
    <p class="subtle" style="margin:0;">Shorter bios and clear headshots scan best on the speaker directory.</p>
    <button type="submit" class="button">Save Speaker</button>
</div>
