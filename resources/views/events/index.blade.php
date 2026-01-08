@extends('layouts.app')

@section('title', 'Ongoing Events - DonorLink')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 style="font-weight: 800;">🎫 Ongoing Events</h2>
        <p style="color: var(--gray-600); max-width: 800px;">
            By buying tickets here, you’re not just attending an event — you’re donating to real campaigns and helping make the world better.
            Choose an event below and support the linked campaign with your ticket purchase.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        @forelse($events as $event)
            <div class="col-md-6">
                <div class="card shadow-sm" style="border-radius: 14px;">
                    <div class="card-body">
                        <h4 style="font-weight: 800;">{{ $event->title }}</h4>

                        <p class="mb-2" style="color: var(--gray-600);">
                            {{ $event->description }}
                        </p>

                        <div class="p-3 mb-3" style="background: var(--gray-50); border-radius: 12px; border: 1px solid var(--gray-200);">
                            <div style="font-weight: 700;">Donation goes to:</div>
                            <div class="mt-1">
                                <a href="{{ route('campaigns.show', $event->campaign) }}" style="font-weight: 700; color: var(--primary);">
                                    {{ $event->campaign->title }}
                                </a>
                            </div>
                            <div style="margin-top: 8px;">
                                <span class="badge bg-primary">Ticket Price: ৳{{ number_format($event->ticket_price, 0) }}</span>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'donor')
                            <form method="POST" action="{{ route('events.buy', $event) }}">
                                @csrf
                                <button class="btn btn-success w-100" style="font-weight: 700;">
                                    🎟️ Buy Ticket & Donate
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning mb-0">
                                Only donors can buy tickets.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p style="color: var(--gray-600);">No ongoing events right now.</p>
        @endforelse
    </div>
</div>
@endsection
