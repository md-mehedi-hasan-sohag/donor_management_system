@extends('layouts.app')

@section('title', 'Donation Impact - DonorLink')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">🌟 Donation Impact</h1>
    <p style="color: var(--gray-600); margin-bottom: 1.5rem;">
        Every donation creates real change. Here’s what different amounts can do — these popups represent real-world impact.
    </p>

    <a href="{{ route('dashboard') }}" class="btn btn-outline">← Back to Dashboard</a>
</div>

{{-- Toast Stack Bottom Right --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="impactToastStack" class="toast-container" style="display:flex; flex-direction:column; gap:12px;">
        @foreach($impacts as $impact)
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">{{ $impact->title }}</strong>
                    <small>
                        {{ $impact->min_amount }}
                        @if($impact->max_amount)

{{ $impact->max_amount }}@else+@endif</small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body">{{ $impact->message }}</div></div>@endforeach</div>
</div>
@endsection
