@extends('layouts.app')

@section('title', 'Community | Admin')

@section('content')
    <section class="panel stack" style="margin-bottom:18px;">
        <div class="admin-header">
            <div>
                <span class="eyebrow">Engagement</span>
                <h2 style="margin: 10px 0 0;">Community</h2>
                <p class="lede" style="margin:0;">Create the discussion prompts, keep the tone curated, and moderate posts without opening the floodgates.</p>
            </div>
            <a href="/admin/community/create" class="button">Add Topic</a>
        </div>

        <div class="stack">
            @foreach ($topics as $topic)
                <article class="card card-loud admin-community-topic">
                    <div>
                        <p class="muted" style="margin:0 0 8px;">{{ $topic->published_posts_count }} published {{ \Illuminate\Support\Str::plural('post', $topic->published_posts_count) }} @if($topic->is_intro) | Core intro topic @endif</p>
                        <h3 style="margin:0 0 8px;">{{ $topic->title }}</h3>
                        <p class="muted" style="margin:0 0 8px;">{{ $topic->prompt }}</p>
                        <p class="muted" style="margin:0;">Status: {{ $topic->is_active ? 'Active' : 'Hidden' }} | Type: {{ ucfirst($topic->type) }} | Sort order: {{ $topic->sort_order }}</p>
                    </div>
                    <div class="admin-card-actions">
                        <a href="/community/topics/{{ $topic->slug }}" class="button secondary admin-card-action-button">View Topic</a>
                        <a href="/admin/community/{{ $topic->slug }}/edit" class="button secondary admin-card-action-button">Edit</a>
                        @if (! $topic->is_intro)
                            <form method="POST" action="/admin/community/{{ $topic->slug }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button secondary admin-card-action-button" onclick="return confirm('Delete this topic and its posts?')">Delete</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel stack">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Moderation</span>
                <h3 style="margin: 12px 0 0; font-size:1.3rem; font-weight:600; letter-spacing:-0.02em;">Recent posts</h3>
            </div>
        </div>

        @forelse ($recentPosts as $post)
            <article class="card">
                <div class="admin-community-topic">
                    <div>
                        <p class="muted" style="margin:0 0 8px;">{{ $post->topic->title }} | {{ $post->user->name }} | {{ $post->created_at?->diffForHumans() }}</p>
                        <p style="margin:0;">{{ $post->body }}</p>
                    </div>
                    <form method="POST" action="/admin/community/posts/{{ $post->id }}" class="stack admin-community-moderation-form">
                        @csrf
                        @method('PATCH')
                        <label for="status-{{ $post->id }}">Visibility</label>
                        <select id="status-{{ $post->id }}" name="status">
                            <option value="published" @selected($post->status === 'published')>Published</option>
                            <option value="hidden" @selected($post->status === 'hidden')>Hidden</option>
                        </select>
                        <label class="check-row">
                            <input type="checkbox" name="is_pinned" value="1" @checked($post->is_pinned)>
                            <span>Pin post</span>
                        </label>
                        <button type="submit" class="button secondary admin-card-action-button">Save</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">No community posts yet.</p>
        @endforelse
    </section>

    @push('page-styles')
        <style>
            .admin-community-topic {
                display: flex;
                justify-content: space-between;
                gap: 16px;
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .admin-community-moderation-form {
                min-width: 220px;
            }
        </style>
    @endpush
@endsection
