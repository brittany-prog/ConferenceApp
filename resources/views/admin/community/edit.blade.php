@extends('layouts.app')

@section('title', 'Edit Community Topic | Admin')

@section('content')
    <section class="panel stack">
        <div>
            <span class="eyebrow">Community</span>
            <h2 style="margin: 10px 0 0;">Edit Topic</h2>
            <p class="lede" style="margin:0;">Update the prompt, visibility, and ordering for this community topic.</p>
        </div>

        <form method="POST" action="/admin/community/{{ $topic->slug }}" class="stack">
            @method('PUT')
            @include('admin.community._form')
        </form>
    </section>
@endsection
