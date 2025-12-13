@extends('layouts.app')

@section('title', 'bKash Payment')

@section('content')
<style>
    .bkash-container {
        max-width: 500px;
        margin: 2rem auto;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bkash-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        width: 100%;
    }

    .bkash-header {
        background: linear-gradient(135deg, #E2136E 0%, #C91160 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .bkash-logo {
        font-size: 2.5rem;
        font-weight: 900;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .bkash-body {
        padding: 2rem;
    }

    .amount-display {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 1.5rem;
        border-radius: 0.75rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .amount-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .amount-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #E2136E;
    }

    .payment-input {
        margin-bottom: 1.5rem;
    }

    .payment-input label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }

    .payment-input input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .payment-input input:focus {
        outline: none;
        border-color: #E2136E;
        box-shadow: 0 0 0 3px rgba(226, 19, 110, 0.1);
    }

    .btn-bkash {
        width: 100%;
        background: linear-gradient(135deg, #E2136E 0%, #C91160 100%);
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-bkash:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(226, 19, 110, 0.4);
    }

    .btn-bkash:active {
        transform: translateY(0);
    }

    .btn-cancel {
        width: 100%;
        background: linear-gradient(135deg, #E2136E 0%, #C91160 100%);
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 0.75rem;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(226, 19, 110, 0.4);
        color: white;
    }

    .btn-cancel:active {
        transform: translateY(0);
    }

    .security-info {
        background: #ecfdf5;
        border-left: 4px solid #10b981;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: #065f46;
    }

    .input-hint {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    [data-theme="dark"] .bkash-card {
        background: var(--card-bg);
    }

    [data-theme="dark"] .payment-input label {
        color: var(--text-primary);
    }

    [data-theme="dark"] .payment-input input {
        background-color: var(--input-bg);
        border-color: var(--input-border);
        color: var(--text-primary);
    }

    [data-theme="dark"] .amount-display {
        background: var(--bg-tertiary);
    }
</style>

<div class="bkash-container">
    <div class="bkash-card">
        <!-- bKash Header -->
        <div class="bkash-header">
            <div class="bkash-logo">bKash</div>
            <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">Secure Payment Gateway</p>
        </div>

        <!-- Payment Body -->
        <div class="bkash-body">
            <!-- Amount Display -->
            <div class="amount-display">
                <div class="amount-label">Payment Amount</div>
                <div class="amount-value">৳{{ number_format($amount, 2) }}</div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('bkash.process', $campaign) }}" method="POST" id="bkashForm">
                @csrf

                <div class="payment-input">
                    <label for="phone">bKash Account Number</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        placeholder="01XXXXXXXXX"
                        maxlength="11"
                        pattern="01[0-9]{9}"
                        required
                        value="{{ old('phone') }}"
                    >
                    <div class="input-hint">Enter your 11-digit bKash mobile number</div>
                    @error('phone')
                        <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="payment-input">
                    <label for="pin">bKash PIN</label>
                    <input
                        type="password"
                        name="pin"
                        id="pin"
                        placeholder="•••••"
                        maxlength="5"
                        pattern="[0-9]{5}"
                        required
                    >
                    <div class="input-hint">Enter your 5-digit bKash PIN</div>
                    @error('pin')
                        <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-bkash" id="payButton">
                    <span id="buttonText">Proceed to Pay</span>
                    <span id="buttonLoader" style="display: none;">Processing...</span>
                </button>

                <a href="{{ route('donations.create', $campaign) }}" class="btn-cancel">Cancel Payment</a>
            </form>

            <!-- Security Info -->
            <div class="security-info">
                <strong style="display: block; margin-bottom: 0.25rem;">🔒 Secure Transaction</strong>
                This is a simulated bKash payment for demonstration purposes. Your actual bKash account will not be charged.
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('bkashForm').addEventListener('submit', function() {
    const button = document.getElementById('payButton');
    const buttonText = document.getElementById('buttonText');
    const buttonLoader = document.getElementById('buttonLoader');

    button.disabled = true;
    buttonText.style.display = 'none';
    buttonLoader.style.display = 'inline';
});

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    e.target.value = value;
});

// Auto-format PIN
document.getElementById('pin').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    e.target.value = value;
});
</script>
@endsection
