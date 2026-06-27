@csrf

<div class="form-section stack">
    <div>
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required>
    </div>

    <div>
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="6" required>{{ old('message', $announcement->message ?? '') }}</textarea>
    </div>
</div>

<div class="form-section stack" style="background: rgba(255, 77, 109, 0.06);">
    <label style="margin-bottom:0;">Email delivery</label>

    <label class="check-row">
        <input type="radio" name="delivery_mode" value="none" {{ old('delivery_mode', 'none') === 'none' ? 'checked' : '' }}>
        <span>
            <strong>Save only</strong><br>
            <span class="subtle">Do not send any email.</span>
        </span>
    </label>

    <label class="check-row">
        <input type="radio" name="delivery_mode" value="test" {{ old('delivery_mode') === 'test' ? 'checked' : '' }}>
        <span>
            <strong>Test</strong><br>
            <span class="subtle">Send only to the currently logged-in admin email.</span>
        </span>
    </label>

    <label class="check-row">
        <input type="radio" name="delivery_mode" value="all" {{ old('delivery_mode') === 'all' ? 'checked' : '' }}>
        <span>
            <strong>Send all</strong><br>
            <span class="subtle">Send to all users with active login access.</span>
        </span>
    </label>
</div>

<div class="action-row">
    <p class="subtle" style="margin:0;">Use test mode first whenever you are changing copy, links, or formatting.</p>
    <button type="submit" class="button">Save Announcement</button>
</div>
