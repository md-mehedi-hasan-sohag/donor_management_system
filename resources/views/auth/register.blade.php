<?php
/*
===================================================================================
FILE: resources/views/auth/register.blade.php
===================================================================================
*/
?>
@extends('layouts.app')

@section('title', 'Register - DonorLink')

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
            <h2>Join DonorLink</h2>
            <p>Create your account and start making a difference</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                <small style="color: var(--gray-500);">Minimum 8 characters</small>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">I want to...</label>
                <select name="role" class="form-control" required>
                    <option value="donor" {{ old('role') == 'donor' ? 'selected' : '' }}>Donate to campaigns (Donor)</option>
                    <option value="recipient" {{ old('role') == 'recipient' ? 'selected' : '' }}>Create campaigns (Recipient)</option>
                </select><br>
            </div>
            <div class="form-group">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            </div>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p>Already have an account? <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign in</a></p>
        </div>
    </div>
</div>
@endsection
