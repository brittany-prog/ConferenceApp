@csrf

<div class="form-section stack">
    <div class="grid grid-2">
        <div>
            <label for="name">Sponsor name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $sponsor->name ?? '') }}" required>
        </div>
        <div>
            <label for="headline">Headline</label>
            <input id="headline" type="text" name="headline" value="{{ old('headline', $sponsor->headline ?? '') }}" placeholder="AI tools for modern teams">
        </div>
        <div>
            <label for="tier">Sponsor tier</label>
            <input id="tier" type="text" name="tier" value="{{ old('tier', $sponsor->tier ?? '') }}" placeholder="Presenting Sponsor">
        </div>
        <div>
            <label for="website_url">Website URL</label>
            <input id="website_url" type="url" name="website_url" value="{{ old('website_url', $sponsor->website_url ?? '') }}">
        </div>
        <div>
            <label for="display_order">Display order</label>
            <input id="display_order" type="number" min="1" max="20" name="display_order" value="{{ old('display_order', $sponsor->display_order ?? 1) }}" required>
        </div>
        <div>
            <label for="booth_location">Booth or table location</label>
            <input id="booth_location" type="text" name="booth_location" value="{{ old('booth_location', $sponsor->booth_location ?? '') }}" placeholder="Student Center lobby">
        </div>
    </div>

    <div>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5">{{ old('description', $sponsor->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-2">
        <div>
            <label for="cta_label">Primary CTA label</label>
            <input id="cta_label" type="text" name="cta_label" value="{{ old('cta_label', $sponsor->cta_label ?? '') }}" placeholder="Book a demo">
        </div>
        <div>
            <label for="cta_url">Primary CTA URL</label>
            <input id="cta_url" type="url" name="cta_url" value="{{ old('cta_url', $sponsor->cta_url ?? '') }}" placeholder="https://example.com/demo">
        </div>
        <div>
            <label for="resource_title">Resource title</label>
            <input id="resource_title" type="text" name="resource_title" value="{{ old('resource_title', $sponsor->resource_title ?? '') }}" placeholder="Download the overview PDF">
        </div>
        <div>
            <label for="resource_url">Resource URL</label>
            <input id="resource_url" type="url" name="resource_url" value="{{ old('resource_url', $sponsor->resource_url ?? '') }}" placeholder="https://example.com/resource.pdf">
        </div>
    </div>

    <div>
        <label for="logo">Sponsor logo</label>
        <input
            id="logo"
            type="file"
            name="logo"
            accept="image/png,image/jpeg,image/jpg,image/webp,image/gif"
            style="display:block;"
        >
        <p class="field-help">PNG, JPG, WEBP, or GIF up to 4MB.</p>
        @if (!empty($sponsor?->logo_path))
            <p class="subtle" style="margin:10px 0;">Current logo</p>
            <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" style="max-width:220px; max-height:120px; object-fit:contain;">
            <label class="check-row" style="margin-top:12px;">
                <input type="checkbox" name="remove_logo" value="1" @checked(old('remove_logo'))>
                <span>Remove current logo</span>
            </label>
        @endif
    </div>

    <label class="check-row">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $sponsor->is_active ?? true))>
        <span>Display sponsor on the homepage</span>
    </label>
</div>

<div class="action-row">
    <p class="subtle" style="margin:0;">Changes here update the public sponsor section after deployment.</p>
    <button type="submit" class="button">Save sponsor</button>
</div>
