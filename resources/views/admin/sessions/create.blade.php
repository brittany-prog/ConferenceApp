@extends('layouts.app')

@section('title', 'Add Session | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/sessions">Back to sessions</a>
            <h2 style="margin: 10px 0 0;">Add session</h2>
            <p class="muted" style="margin:8px 0 0;">Create agenda entries with clear timing, speaker details, and location information for attendees.</p>
        </div>

        <form method="POST" action="/admin/sessions" class="stack">
            @include('admin.sessions._form')
        </form>
    </section>
@endsection
