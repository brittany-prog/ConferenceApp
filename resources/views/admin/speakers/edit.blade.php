@extends('layouts.app')

@section('title', 'Edit Speaker | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/speakers">Back to speakers</a>
            <h2 style="margin: 10px 0 0;">Edit speaker</h2>
            <p class="muted" style="margin:8px 0 0;">Keep the speaker directory polished and recognizable for attendees.</p>
        </div>

        <form method="POST" action="/admin/speakers/{{ $speaker->id }}" enctype="multipart/form-data" class="stack">
            @csrf
            @method('PUT')
            @include('admin.speakers._form')
        </form>
    </section>
@endsection
