@extends('layouts.app')

@section('title', 'Edit Session | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack">
        <div>
            <a href="/admin/sessions">Back to sessions</a>
            <h2 style="margin: 10px 0 0;">Edit session</h2>
            <p class="muted" style="margin:8px 0 0;">Adjust timing, speakers, or placement without leaving the admin workflow.</p>
        </div>

        <form method="POST" action="/admin/sessions/{{ $session->id }}" class="stack">
            @csrf
            @method('PUT')
            @include('admin.sessions._form')
        </form>
    </section>
@endsection
