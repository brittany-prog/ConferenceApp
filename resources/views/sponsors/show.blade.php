@extends('layouts.app')

@section('title', $sponsor->name.' | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 980px; margin: 0 auto;">
        <a href="/sponsors">Back to sponsors & exhibitors</a>

        <div class="grid grid-2" style="align-items:start;">
            <div class="card card-loud" style="text-align:center;">
                @if ($sponsor->logo_path)
                    <img src="{{ asset('storage/'.$sponsor->logo_path) }}" alt="{{ $sponsor->name }} logo" style="width:min(100%, 280px); max-height:150px; object-fit:contain; margin:0 auto 18px;">
                @endif
                @if ($sponsor->tier)
                    <span class="eyebrow">{{ $sponsor->tier }}</span>
                @endif
                @if ($sponsor->exhibitors_count > 0)
                    <div class="meta-stack" style="justify-content:center; margin-top:12px;">
                        <span class="meta-pill">Exhibitor partner</span>
                    </div>
                @endif
                <h2 style="margin:14px 0 8px;">{{ $sponsor->name }}</h2>
                @if ($sponsor->headline)
                    <p class="muted" style="margin:0;">{{ $sponsor->headline }}</p>
                @endif
                @if ($sponsor->booth_location)
                    <div class="meta-stack" style="justify-content:center; margin-top:12px;">
                        <span class="meta-pill">Find them at {{ $sponsor->booth_location }}</span>
                    </div>
                @endif
            </div>

            <div class="stack">
                <div class="card card-loud">
                    <p class="muted" style="margin:0 0 10px;">About this sponsor</p>
                    <p style="margin:0;">{{ $sponsor->description ?: 'More sponsor details coming soon.' }}</p>
                </div>

                @if ($sponsor->exhibitors->isNotEmpty())
                    <div class="card">
                        <p class="muted" style="margin:0 0 12px;">Exhibitor representatives</p>
                        <div class="sponsor-action-row">
                            @foreach ($sponsor->exhibitors as $exhibitor)
                                <a href="/attendees/{{ $exhibitor->id }}" class="button secondary sponsor-action-button">{{ $exhibitor->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card">
                    <p class="muted" style="margin:0 0 12px;">Connect</p>
                    <div class="sponsor-action-row">
                        @if ($sponsor->website_url)
                            <a href="{{ $sponsor->website_url }}" target="_blank" rel="noreferrer" class="button secondary sponsor-action-button">Visit website</a>
                        @endif
                        @if ($sponsor->cta_label && $sponsor->cta_url)
                            <a href="{{ $sponsor->cta_url }}" target="_blank" rel="noreferrer" class="button sponsor-action-button">{{ $sponsor->cta_label }}</a>
                        @endif
                    </div>
                </div>

                @if ($sponsor->resource_title && $sponsor->resource_url)
                    <div class="card">
                        <p class="muted" style="margin:0 0 8px;">Featured resource</p>
                        <a href="{{ $sponsor->resource_url }}" target="_blank" rel="noreferrer">{{ $sponsor->resource_title }}</a>
                    </div>
                @endif

                <div class="card">
                    <p class="muted" style="margin:0 0 8px;">Interest</p>
                    @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                        <p style="margin:0 0 12px;">{{ $sponsor->interested_users_count }} attendee{{ $sponsor->interested_users_count === 1 ? '' : 's' }} saved this sponsor so far.</p>
                    @else
                        <p style="margin:0 0 12px;">Save this sponsor if you want to come back to their profile or follow up after the event.</p>
                    @endif
                    <form method="POST" action="/sponsors/{{ $sponsor->id }}/interest">
                        @csrf
                        <button type="submit" class="button secondary sponsor-interest-button @if (! $isInterested) sponsor-interest-button--active @endif">
                            {{ $isInterested ? 'Saved to your interested list' : 'I’m interested' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('page-styles')
        <style>
            .sponsor-action-row {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .sponsor-action-button,
            .sponsor-interest-button {
                min-height: 48px;
                justify-content: center;
            }

            .button.secondary.sponsor-interest-button--active {
                background: linear-gradient(135deg, rgba(229, 242, 237, 0.98), rgba(244, 250, 247, 1));
                border-color: rgba(163, 194, 183, 0.55);
                color: #23443d;
                box-shadow: 0 12px 24px rgba(110, 154, 140, 0.1);
            }

            @media (max-width: 720px) {
                .sponsor-action-row {
                    flex-direction: column;
                }

                .sponsor-action-button,
                .sponsor-interest-button {
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
