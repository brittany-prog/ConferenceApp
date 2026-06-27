@extends('layouts.app')

@section('title', 'Edit Announcement | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/announcements">Back to announcements</a>
            <h2 style="margin: 10px 0 0;">Edit announcement</h2>
            <p class="muted" style="margin:8px 0 0;">Update the message and decide whether it should stay in-app only or send by email.</p>
        </div>

        <form method="POST" action="/admin/announcements/{{ $announcement->id }}" class="stack">
            @csrf
            @method('PUT')
            @include('admin.announcements._form')
        </form>
    </section>
@endsection
