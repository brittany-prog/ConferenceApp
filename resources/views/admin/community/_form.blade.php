@csrf

<div class="form-section stack">
    <div class="grid grid-2">
        <div>
            <label for="title">Topic title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $topic->title ?? '') }}" required>
        </div>
        <div>
            <label for="type">Topic type</label>
            <select id="type" name="type" required>
                <option value="discussion" @selected(old('type', $topic->type ?? 'discussion') === 'discussion')>Discussion</option>
                <option value="meetup" @selected(old('type', $topic->type ?? 'discussion') === 'meetup')>Meet-up</option>
                <option value="organizer" @selected(old('type', $topic->type ?? 'discussion') === 'organizer')>Organizer Prompt</option>
            </select>
        </div>
        <div>
            <label for="sort_order">Sort order</label>
            <input id="sort_order" type="number" min="1" max="99" name="sort_order" value="{{ old('sort_order', $topic->sort_order ?? 1) }}" required>
        </div>
        <div>
            <label class="check-row" style="margin-top:32px;">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $topic->is_active ?? true))>
                <span>Visible to attendees</span>
            </label>
        </div>
    </div>

    <div>
        <label for="description">Short description</label>
        <textarea id="description" name="description" rows="3">{{ old('description', $topic->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="prompt">Prompt copy</label>
        <textarea id="prompt" name="prompt" rows="4">{{ old('prompt', $topic->prompt ?? '') }}</textarea>
    </div>
</div>

<div class="action-row">
    <p class="subtle" style="margin:0;">Discussion prompts work best when they are specific, welcoming, and practical.</p>
    <button type="submit" class="button">Save topic</button>
</div>
