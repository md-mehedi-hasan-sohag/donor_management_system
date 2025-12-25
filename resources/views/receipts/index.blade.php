@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📬 Receipt Inbox</h2>

    @forelse($receipts as $receipt)
        <a href="{{ route('receipts.show', $receipt) }}"
           style="display:block; padding:1rem; margin-bottom:0.5rem;
                  background: {{ $receipt->is_read ? '#f9fafb' : '#e0f2fe' }};
                  border-radius:0.5rem; text-decoration:none; color:black;">
            <strong>{{ $receipt->subject }}</strong><br>
            <small>{{ $receipt->created_at->diffForHumans() }}</small>
        </a>
    @empty
        <p>No receipts yet.</p>
    @endforelse
</div>
@endsection
