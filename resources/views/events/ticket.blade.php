@extends('layouts.app')

@section('title', 'Your Ticket - DonorLink')

@section('content')
<div class="container py-5" style="max-width: 800px;">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h3 style="font-weight: 900;">✅ Ticket Confirmed</h3>
            <p style="color: var(--gray-600); margin-bottom: 20px;">
                Thank you for the donation! Your ticket purchase has been recorded successfully.
            </p>

            <div class="p-3" style="background: var(--gray-50); border-radius: 12px; border: 1px solid var(--gray-200);">
                <div><strong>Event:</strong> {{ $ticket->event->title }}</div>
                <div><strong>Amount Donated:</strong> ৳{{ number_format($ticket->amount, 0) }}</div>
                <div><strong>Ticket Code:</strong> <span style="font-weight: 900;">{{ $ticket->ticket_code }}</span></div>
                <div><strong>Purchased:</strong> {{ $ticket->purchased_at->format('d M Y, h:i A') }}</div>
                <hr>
                <div><strong>Donation Campaign:</strong></div>
                <a href="{{ route('campaigns.show', $ticket->campaign) }}" style="font-weight: 800; color: var(--primary);">
                    {{ $ticket->campaign->title }}
                </a>
            </div>

            <a href="{{ route('events.index') }}" class="btn btn-outline-primary w-100 mt-3">
                🔥 Check More Ongoing Events
            </a>
        </div>
    </div>
</div>
@endsection
