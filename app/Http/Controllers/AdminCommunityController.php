<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCommunityController extends Controller
{
    public function index()
    {
        $topics = CommunityTopic::query()
            ->withCount(['posts as published_posts_count' => fn ($query) => $query->where('status', 'published')])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $recentPosts = CommunityPost::query()
            ->with(['user', 'topic', 'parent'])
            ->latest()
            ->take(18)
            ->get();

        return view('admin.community.index', compact('topics', 'recentPosts'));
    }

    public function create()
    {
        $topic = new CommunityTopic([
            'is_active' => true,
            'type' => 'discussion',
        ]);

        return view('admin.community.create', compact('topic'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTopic($request);
        $validated['slug'] = $this->buildUniqueSlug($validated['title']);
        $validated['created_by'] = $request->user()->id;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_intro'] = false;

        CommunityTopic::create($validated);

        return redirect('/admin/community')->with('success', 'Community topic created successfully.');
    }

    public function edit(CommunityTopic $topic)
    {
        return view('admin.community.edit', compact('topic'));
    }

    public function update(Request $request, CommunityTopic $topic)
    {
        $validated = $this->validateTopic($request);
        $validated['is_active'] = $request->boolean('is_active');

        if ($topic->title !== $validated['title']) {
            $validated['slug'] = $this->buildUniqueSlug($validated['title'], $topic->id);
        }

        $topic->update($validated);

        return redirect('/admin/community')->with('success', 'Community topic updated successfully.');
    }

    public function destroy(CommunityTopic $topic)
    {
        abort_if($topic->is_intro, 403, 'The introduction topic cannot be deleted.');

        $topic->delete();

        return redirect('/admin/community')->with('success', 'Community topic deleted successfully.');
    }

    public function moderatePost(Request $request, CommunityPost $post)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:published,hidden'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        $post->update([
            'status' => $validated['status'],
            'is_pinned' => $request->boolean('is_pinned') && $validated['status'] === 'published',
        ]);

        return back()->with('success', 'Community post updated.');
    }

    private function validateTopic(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:600'],
            'prompt' => ['nullable', 'string', 'max:1200'],
            'type' => ['required', 'in:discussion,meetup,organizer'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:99'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function buildUniqueSlug(string $title, ?int $ignoreTopicId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (CommunityTopic::query()
            ->where('slug', $slug)
            ->when($ignoreTopicId, fn ($query) => $query->where('id', '!=', $ignoreTopicId))
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
