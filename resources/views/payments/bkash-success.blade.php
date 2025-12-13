@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<style>
    .success-container {
        max-width: 600px;
        margin: 3rem auto;
        text-align: center;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .checkmark {
        font-size: 3rem;
        color: white;
    }

    .success-card {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .transaction-details {
        background: var(--gray-50);
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .detail-value {
        font-weight: 600;
        color: var(--gray-900);
    }

    .bkash-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #E2136E;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 0.875rem;
    }

    [data-theme="dark"] .success-card {
        background: var(--card-bg);
    }

    [data-theme="dark"] .detail-value {
        color: var(--text-primary);
    }

    [data-theme="dark"] .transaction-details {
        background: var(--bg-tertiary);
    }
</style>

<div class="success-container">
    <div class="success-icon">
        <div class="checkmark">✓</div>
    </div>

    <div class="success-card">
        <h1 style="font-size: 2rem; font-weight: 700; color: #10b981; margin-bottom: 0.5rem;">
            Payment Successful!
        </h1>
        <p style="color: var(--gray-600); font-size: 1.125rem; margin-bottom: 2rem;">
            Your donation has been processed successfully
        </p>

        <div class="transaction-details">
            <div class="detail-row">
                <span class="detail-label">Transaction ID</span>
                <span class="detail-value">{{ $donation->transaction_id }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">
                    <span class="bkash-badge">bKash</span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Amount Donated</span>
                <span class="detail-value" style="color: #10b981; font-size: 1.25rem;">
                    ${{ number_format($donation->amount, 2) }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Campaign</span>
                <span class="detail-value">{{ $donation->campaign->title }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date & Time</span>
                <span class="detail-value">{{ $donation->created_at->format('M d, Y - h:i A') }}</span>
            </div>

            @if($donation->is_recurring)
            <div class="detail-row">
                <span class="detail-label">Recurring</span>
                <span class="detail-value">
                    <span class="badge badge-info">{{ ucfirst($donation->recurring_frequency) }}</span>
                </span>
            </div>
            @endif
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <a href="{{ route('donations.receipt', $donation) }}" class="btn btn-primary" style="flex: 1;">
                View Receipt
            </a>
            <a href="{{ route('campaigns.show', $donation->campaign) }}" class="btn btn-outline" style="flex: 1;">
                Back to Campaign
            </a>
        </div>

        <div style="margin-top: 1.5rem; padding: 1rem; background: #ecfdf5; border-radius: 0.5rem; border-left: 4px solid #10b981;">
            <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                <strong>Thank you for your generosity!</strong> A confirmation email has been sent to your registered email address.
            </p>
        </div>
    </div>
</div>
@endsection
