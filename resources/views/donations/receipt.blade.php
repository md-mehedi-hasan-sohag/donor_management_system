@extends('layouts.app')

@section('title', 'Donation Receipt')

@section('content')
<style>
    .receipt-container {
        max-width: 700px;
        margin: 2rem auto;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .receipt-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }

    .receipt-body {
        padding: 2rem;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .receipt-row:last-child {
        border-bottom: none;
    }

    .print-btn {
        display: none;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .receipt-container, .receipt-container * {
            visibility: visible;
        }
        .receipt-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
        }
        .print-btn, .header, .footer {
            display: none !important;
        }
    }
</style>

<div class="receipt-container">
    <div class="receipt-header">
        <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Thank You!</h1>
        <p style="font-size: 1.125rem; opacity: 0.9;">Your donation has been received</p>
    </div>

    <div class="receipt-body">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                ${{ number_format($donation->amount, 2) }}
            </div>
            <p style="color: var(--gray-600);">Donation Amount</p>
        </div>

        <div style="background: var(--gray-50); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
            <h3 style="font-weight: 600; margin-bottom: 1rem;">Donation Details</h3>
            
            <div class="receipt-row">
                <span style="color: var(--gray-600);">Campaign:</span>
                <strong>{{ $donation->campaign->title }}</strong>
            </div>

            <div class="receipt-row">
                <span style="color: var(--gray-600);">Donor:</span>
                <strong>{{ $donation->getDonorDisplayName() }}</strong>
            </div>

            <div class="receipt-row">
                <span style="color: var(--gray-600);">Transaction ID:</span>
                <strong style="font-family: monospace;">{{ $donation->transaction_id }}</strong>
            </div>

            <div class="receipt-row">
                <span style="color: var(--gray-600);">Date:</span>
                <strong>{{ $donation->created_at->format('F d, Y \a\t g:i A') }}</strong>
            </div>

            <div class="receipt-row">
                <span style="color: var(--gray-600);">Payment Method:</span>
                <strong>{{ ucfirst($donation->payment_method) }}</strong>
            </div>

            @if($donation->is_recurring)
            <div class="receipt-row">
                <span style="color: var(--gray-600);">Recurring:</span>
                <strong>Yes ({{ ucfirst($donation->recurring_frequency) }})</strong>
            </div>
            @endif
        </div>

        <div style="background: #ecfdf5; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #10b981; margin-bottom: 2rem;">
            <h3 style="font-weight: 600; margin-bottom: 1rem; color: #065f46;">💚 Breakdown</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #065f46;">Your donation:</span>
                <strong style="color: #065f46;">${{ number_format($donation->amount, 2) }}</strong>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #6b7280;">Platform fee:</span>
                <span style="color: #6b7280;">${{ number_format($donation->platform_fee, 2) }}</span>
            </div>

            <div style="border-top: 2px solid #10b981; padding-top: 0.5rem; margin-top: 0.5rem; display: flex; justify-content: space-between;">
                <strong style="color: #065f46;">Campaign receives:</strong>
                <strong style="color: #10b981; font-size: 1.125rem;">${{ number_format($donation->net_amount, 2) }}</strong>
            </div>
        </div>

        @if($donation->message)
        <div style="background: var(--gray-50); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Your Message</h3>
            <p style="color: var(--gray-700); font-style: italic;">"{{ $donation->message }}"</p>
        </div>
        @endif

        <div style="text-align: center; padding: 1.5rem; background: #dbeafe; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <p style="color: #1e40af; font-weight: 600; margin-bottom: 0.5rem;">📧 Receipt sent to your email</p>
            <p style="color: #1e40af; font-size: 0.875rem;">A copy of this receipt has been sent to {{ auth()->user()->email }}</p>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('campaigns.show', $donation->campaign) }}" class="btn btn-primary" style="flex: 1; text-align: center;">View Campaign</a>
            <button onclick="window.print()" class="btn btn-outline" style="flex: 1;">🖨️ Print Receipt</button>
        </div>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <p style="color: var(--gray-600); font-size: 0.875rem;">
                Questions? Contact us at support@donorlink.com
            </p>
        </div>
    </div>
</div>
@endsection