@extends('layouts.app')

@section('title', 'Reset Password - DonorLink')

@section('content')
<style>
    .auth-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .auth-card {
        background: white;
        padding: 3rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        max-width: 450px;
        width: 100%;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .auth-header p {
        color: var(--gray-600);
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Set New Password</h2>
            <p>Enter your new password below</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $email }}" readonly style="background-color: #f5f5f5;">
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required autofocus placeholder="Enter new password (min. 8 characters)">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-enter your new password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Reset Password</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p>Remember your password? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign in</a></p>
        </div>
    </div>
</div>
@endsection
