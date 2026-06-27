@extends('layouts.app')

@section('title', 'Add Sponsor | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/sponsors">Back to sponsors</a>
            <h2 style="margin: 10px 0 0;">Add sponsor</h2>
            <p class="muted" style="margin:8px 0 0;">Use high-contrast logos and concise descriptions for the clearest homepage presentation.</p>
        </div>

        <form method="POST" action="/admin/sponsors" enctype="multipart/form-data" class="stack">
            @include('admin.sponsors._form')
        </form>
    </section>
@endsection
