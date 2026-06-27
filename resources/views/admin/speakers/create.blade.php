@extends('layouts.app')

@section('title', 'Add Speaker | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/speakers">Back to speakers</a>
            <h2 style="margin: 10px 0 0;">Add speaker</h2>
            <p class="muted" style="margin:8px 0 0;">Create a speaker profile with a readable title, organization, and photo.</p>
        </div>

        <form method="POST" action="/admin/speakers" enctype="multipart/form-data" class="stack">
            @include('admin.speakers._form')
        </form>
    </section>
@endsection
