@extends('layouts.app')

@section('title', 'Add Announcement | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/announcements">Back to announcements</a>
            <h2 style="margin: 10px 0 0;">Add announcement</h2>
            <p class="muted" style="margin:8px 0 0;">Post an update in the app and choose whether to test it first or email everyone with access.</p>
        </div>

        <form method="POST" action="/admin/announcements" class="stack">
            @include('admin.announcements._form')
        </form>
    </section>
@endsection
