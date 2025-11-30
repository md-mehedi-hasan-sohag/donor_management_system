<?php
/*
===================================================================================
FILE: resources/views/auth/login.blade.php
===================================================================================
*/
?>
@extends('layouts.app')

@section('title', 'Login - DonorLink')

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
            <h2>Welcome Back</h2>
            <p>Sign in to your DonorLink account</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration: none;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p>Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign up</a></p>
        </div>
    </div>
</div>
@endsection