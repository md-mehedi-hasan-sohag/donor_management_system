@extends('layouts.app')

@section('title', 'Forgot Password - DonorLink')

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
            <h2>Reset Your Password</h2>
            <p>Enter your email address and we'll send you a link to reset your password</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">📧 Send Reset Link</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p>Remember your password? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign in</a></p>
        </div>
    </div>
</div>
@endsection