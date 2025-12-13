@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<style>
    .failed-container {
        max-width: 600px;
        margin: 3rem auto;
        text-align: center;
    }

    .failed-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        animation: shake 0.5s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .cross-mark {
        font-size: 3rem;
        color: white;
    }

    .failed-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .error-info {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
        text-align: left;
    }

    .common-reasons {
        text-align: left;
        background: var(--gray-50);
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
    }

    .common-reasons ul {
        margin: 1rem 0 0 0;
        padding-left: 1.5rem;
    }

    .common-reasons li {
        margin-bottom: 0.5rem;
        color: var(--gray-700);
    }

    [data-theme="dark"] .failed-card {
        background: var(--card-bg);
    }

    [data-theme="dark"] .common-reasons {
        background: var(--bg-tertiary);
    }

    [data-theme="dark"] .common-reasons li {
        color: var(--text-secondary);
    }
</style>

<div class="failed-container">
    <div class="failed-icon">
        <div class="cross-mark">✕</div>
    </div>

    <div class="failed-card">
        <h1 style="font-size: 2rem; font-weight: 700; color: #ef4444; margin-bottom: 0.5rem;">
            Payment Failed
        </h1>
        <p style="color: var(--gray-600); font-size: 1.125rem; margin-bottom: 2rem;">
            We couldn't process your bKash payment
        </p>

        <div class="error-info">
            <strong style="display: block; margin-bottom: 0.5rem; color: #991b1b;">
                Transaction Not Completed
            </strong>
            <p style="margin: 0; color: #7f1d1d; font-size: 0.875rem;">
                {{ session('error', 'The payment could not be processed. Please check your details and try again.') }}
            </p>
        </div>

        <div class="common-reasons">
            <strong style="display: block; margin-bottom: 0.5rem; color: var(--gray-900);">
                Common Reasons for Payment Failure:
            </strong>
            <ul>
                <li>Insufficient balance in bKash account</li>
                <li>Incorrect bKash PIN entered</li>
                <li>Network connectivity issues</li>
                <li>Daily transaction limit exceeded</li>
                <li>Account temporarily locked</li>
            </ul>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <a href="{{ route('bkash.payment', $campaign) }}" class="btn btn-primary" style="flex: 1;">
                Try Again
            </a>
            <a href="{{ route('donations.create', $campaign) }}" class="btn btn-outline" style="flex: 1;">
                Change Payment Method
            </a>
        </div>

        <a href="{{ route('campaigns.show', $campaign) }}" style="display: block; margin-top: 1rem; color: var(--gray-600); text-decoration: none;">
            ← Back to Campaign
        </a>

        <div style="margin-top: 1.5rem; padding: 1rem; background: #dbeafe; border-radius: 0.5rem; border-left: 4px solid #3b82f6;">
            <p style="margin: 0; color: #1e40af; font-size: 0.875rem;">
                <strong>Need Help?</strong> If you continue to experience issues, please contact our support team or try a different payment method.
            </p>
        </div>
    </div>
</div>
@endsection
