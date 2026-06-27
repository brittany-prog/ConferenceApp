@extends('layouts.app')

@section('title', 'Edit Sponsor | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/sponsors">Back to sponsors</a>
            <h2 style="margin: 10px 0 0;">Edit sponsor</h2>
            <p class="muted" style="margin:8px 0 0;">Update the logo, copy, or placement without changing the rest of the homepage layout.</p>
        </div>

        <form method="POST" action="/admin/sponsors/{{ $sponsor->id }}" enctype="multipart/form-data" class="stack">
            @csrf
            @method('PUT')
            @include('admin.sponsors._form')
        </form>
    </section>
@endsection
