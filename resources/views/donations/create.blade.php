@extends('layouts.app')

@section('title', 'Donate to ' . $campaign->title)

@section('content')
<style>
    .donation-container {
        max-width: 800px;
        margin: 2rem auto;
    }

    .donation-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
    }

    .amount-option {
        padding: 1.5rem;
        border: 3px solid var(--gray-300);
        border-radius: 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }

    .amount-option:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    .amount-option.selected {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    }

    .amount-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }

    .impact-card {
        background: #ecfdf5;
        border-left: 4px solid #10b981;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }

    .payment-method {
        padding: 1rem;
        border: 2px solid var(--gray-300);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-method:hover {
        border-color: var(--primary);
        background: var(--gray-50);
    }

    .payment-method.selected {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.05);
    }
</style>

<div class="donation-container">
    <!-- Campaign Summary -->
    <div class="donation-summary">
        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">Support This Campaign</h1>
        <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; opacity: 0.9;">{{ $campaign->title }}</h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">${{ number_format($campaign->current_amount, 0) }}</div>
                <div style="opacity: 0.8; font-size: 0.875rem;">Raised</div>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">{{ $campaign->progressPercentage() }}%</div>
                <div style="opacity: 0.8; font-size: 0.875rem;">Funded</div>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 700;">{{ $campaign->daysRemaining() }}</div>
                <div style="opacity: 0.8; font-size: 0.875rem;">Days Left</div>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <form action="{{ route('donations.store', $campaign) }}" method="POST" id="donationForm">
            @csrf

            <!-- Donation Type -->
            <div class="form-group">
                <label class="form-label">I want to donate:</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="payment-method selected" onclick="selectDonationType('monetary')">
                        <input type="radio" name="donation_type" value="monetary" checked style="display: none;">
                        <span style="font-size: 2rem;">💰</span>
                        <div>
                            <strong style="display: block;">Money</strong>
                            <small style="color: var(--gray-600);">Make a monetary contribution</small>
                        </div>
                    </label>
                    
                    @if($campaign->accepts_in_kind)
                    <label class="payment-method" onclick="selectDonationType('in_kind')">
                        <input type="radio" name="donation_type" value="in_kind" style="display: none;">
                        <span style="font-size: 2rem;">📦</span>
                        <div>
                            <strong style="display: block;">Items</strong>
                            <small style="color: var(--gray-600);">Donate physical items</small>
                        </div>
                    </label>
                    @endif
                </div>
            </div>

            <!-- Monetary Donation Section -->
            <div id="monetarySection">
                <div class="form-group">
                    <label class="form-label">Select Amount</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="amount-option" onclick="selectAmount(25)">
                            <div class="amount-value">$25</div>
                        </div>
                        <div class="amount-option" onclick="selectAmount(50)">
                            <div class="amount-value">$50</div>
                        </div>
                        <div class="amount-option" onclick="selectAmount(100)">
                            <div class="amount-value">$100</div>
                        </div>
                        <div class="amount-option" onclick="selectAmount(250)">
                            <div class="amount-value">$250</div>
                        </div>
                        <div class="amount-option" onclick="selectAmount(500)">
                            <div class="amount-value">$500</div>
                        </div>
                        <div class="amount-option" onclick="selectAmount(1000)">
                            <div class="amount-value">$1000</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Or enter custom amount</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-size: 1.25rem; font-weight: 600; color: var(--gray-600);">$</span>
                        <input type="number" name="amount" id="amountInput" class="form-control" style="padding-left: 2.5rem; font-size: 1.25rem; font-weight: 600;" placeholder="0.00" min="5" step="0.01" oninput="updateBreakdown()">
                    </div>
                </div>

                <!-- Donation Breakdown -->
                <div class="impact-card" id="breakdown" style="display: none;">
                    <h3 style="font-weight: 600; margin-bottom: 1rem; color: #065f46;">💚 Your Impact</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Your donation:</span>
                        <strong id="donationAmount">$0.00</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #6b7280;">
                        <span>Platform fee (2.5%):</span>
                        <span id="platformFee">$0.00</span>
                    </div>
                    <div style="border-top: 2px solid #10b981; padding-top: 0.5rem; margin-top: 0.5rem; display: flex; justify-content: space-between;">
                        <strong>Campaign receives:</strong>
                        <strong id="netAmount" style="color: #10b981; font-size: 1.125rem;">$0.00</strong>
                    </div>
                </div>

                <!-- Recurring Option -->
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_recurring" value="1" id="recurringCheckbox" onchange="toggleRecurring()">
                        <span class="form-label" style="margin: 0;">🔄 Make this a recurring donation</span>
                    </label>
                </div>

                <div class="form-group" id="recurringOptions" style="display: none;">
                    <label class="form-label">Frequency</label>
                    <select name="recurring_frequency" class="form-control">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly (Every 3 months)</option>
                        <option value="weekly">Weekly</option>
                    </select>
                    <small style="color: var(--gray-600);">You can cancel anytime from your dashboard</small>
                </div>
            </div>

            <!-- In-Kind Donation Section -->
            <div id="inKindSection" style="display: none;">
                <div class="form-group">
                    <label class="form-label">What items are you donating?</label>
                    <textarea name="in_kind_items" class="form-control" rows="4" placeholder="Please describe the items you wish to donate..."></textarea>
                </div>

                @if($campaign->in_kind_needs)
                <div style="background: var(--gray-50); padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                    <strong style="display: block; margin-bottom: 0.5rem;">Items Needed:</strong>
                    <p style="color: var(--gray-700);">{{ $campaign->in_kind_needs }}</p>
                </div>
                @endif
            </div>

            <!-- Additional Options -->
            <div class="form-group">
                <label class="form-label">Add a message (optional)</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Share why you're supporting this cause..."></textarea>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_anonymous" value="1">
                    <span>🕶️ Make my donation anonymous</span>
                </label>
                <small style="color: var(--gray-600); display: block; margin-top: 0.25rem; margin-left: 1.75rem;">
                    Your name won't be displayed publicly
                </small>
            </div>

            <!-- Payment Method Selection -->
            <div class="form-group" id="paymentMethodSection">
                <label class="form-label">Select Payment Method</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="payment-method selected" onclick="selectPaymentMethod('bkash')">
                        <input type="radio" name="payment_method" value="bkash" checked style="display: none;">
                        <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                            <div style="width: 60px; height: 60px; background: #E2136E; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 1rem;">
                                bKash
                            </div>
                            <div style="flex: 1;">
                                <strong style="display: block;">bKash</strong>
                                <small style="color: var(--gray-600);">Mobile Payment</small>
                            </div>
                        </div>
                    </label>

                    <label class="payment-method" onclick="selectPaymentMethod('nagad')">
                        <input type="radio" name="payment_method" value="nagad" style="display: none;">
                        <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                            <div style="width: 60px; height: 60px; background: #E93E3A; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 1rem;">
                                Nagad
                            </div>
                            <div style="flex: 1;">
                                <strong style="display: block;">Nagad</strong>
                                <small style="color: var(--gray-600);">Mobile Payment</small>
                            </div>
                        </div>
                    </label>

                    <label class="payment-method" onclick="selectPaymentMethod('card')">
                        <input type="radio" name="payment_method" value="card" style="display: none;">
                        <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                <svg width="32" height="32" fill="white" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                                </svg>
                            </div>
                            <div style="flex: 1;">
                                <strong style="display: block;">Credit/Debit Card</strong>
                                <small style="color: var(--gray-600);">Visa, MasterCard, Amex</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Payment Info -->
            <div style="background: #dbeafe; padding: 1.5rem; border-radius: 0.5rem; margin: 1.5rem 0; border-left: 4px solid #3b82f6;">
                <div style="display: flex; gap: 1rem; align-items: start;">
                    <span style="font-size: 2rem;">🔒</span>
                    <div>
                        <strong style="display: block; margin-bottom: 0.25rem; color: #1e40af;">Secure Payment</strong>
                        <p style="color: #1e40af; font-size: 0.875rem; margin: 0;">
                            Your payment information is secure and encrypted. We never store your payment details.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.125rem; padding: 1rem;">
                ❤️ Complete Donation
            </button>

            <p style="text-align: center; color: var(--gray-600); font-size: 0.875rem; margin-top: 1rem;">
                By donating, you agree to our Terms of Service and Privacy Policy
            </p>
        </form>
    </div>
</div>

<script>
function selectAmount(amount) {
    document.querySelectorAll('.amount-option').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('amountInput').value = amount;
    updateBreakdown();
}

function updateBreakdown() {
    const amount = parseFloat(document.getElementById('amountInput').value) || 0;
    const breakdown = document.getElementById('breakdown');
    
    if (amount > 0) {
        breakdown.style.display = 'block';
        const fee = amount * 0.025;
        const net = amount - fee;
        
        document.getElementById('donationAmount').textContent = '$' + amount.toFixed(2);
        document.getElementById('platformFee').textContent = '$' + fee.toFixed(2);
        document.getElementById('netAmount').textContent = '$' + net.toFixed(2);
    } else {
        breakdown.style.display = 'none';
    }
}

function selectDonationType(type) {
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');

    const paymentMethodSection = document.getElementById('paymentMethodSection');

    if (type === 'monetary') {
        document.getElementById('monetarySection').style.display = 'block';
        document.getElementById('inKindSection').style.display = 'none';
        paymentMethodSection.style.display = 'block';
    } else {
        document.getElementById('monetarySection').style.display = 'none';
        document.getElementById('inKindSection').style.display = 'block';
        paymentMethodSection.style.display = 'none';
    }
}

function toggleRecurring() {
    const checkbox = document.getElementById('recurringCheckbox');
    const options = document.getElementById('recurringOptions');
    options.style.display = checkbox.checked ? 'block' : 'none';
}

function selectPaymentMethod(method) {
    document.querySelectorAll('#paymentMethodSection .payment-method').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
}
</script>
@endsection