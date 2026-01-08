@extends('layouts.app')

@section('content')
<div class="container">


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('referrals.generate') }}">
        @csrf
        <button class="btn btn-primary">Generate Referral Link</button>
    </form>

    @if($latest)
        <div class="mt-3">
            <p><b>Your Referral Link:</b></p>
            <input class="form-control" value="{{ url('/register?ref=' . $latest->code) }}" readonly>
            <small>Status: {{ $latest->status }}</small>
        </div>
    @endif
</div>
@endsection
