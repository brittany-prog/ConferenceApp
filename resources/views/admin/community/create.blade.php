@extends('layouts.app')

@section('title', 'Create Community Topic | Admin')

@section('content')
    <section class="panel stack">
        <div>
            <span class="eyebrow">Community</span>
            <h2 style="margin: 10px 0 0;">Add Topic</h2>
            <p class="lede" style="margin:0;">Create a guided prompt attendees can respond to without opening a free-for-all board.</p>
        </div>

        <form method="POST" action="/admin/community" class="stack">
            @include('admin.community._form')
        </form>
    </section>
@endsection
