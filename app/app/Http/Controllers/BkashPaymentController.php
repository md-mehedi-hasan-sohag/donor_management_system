<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\DonationService;
use Illuminate\Http\Request;

class BkashPaymentController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    /**
     * Show bKash payment page
     */
    public function show(Campaign $campaign)
    {
        // Check if there's pending donation data
        $pendingDonation = session('pending_donation');

        if (!$pendingDonation || $pendingDonation['campaign_id'] !== $campaign->id) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'No pending donation found.');
        }

        $amount = $pendingDonation['data']['amount'];

        return view('payments.bkash', compact('campaign', 'amount'));
    }

    /**
     * Process bKash payment (simulation)
     */
    public function process(Request $request, Campaign $campaign)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^01[0-9]{9}$/',
            'pin' => 'required|string|size:5',
        ]);

        // Get pending donation data
        $pendingDonation = session('pending_donation');

        if (!$pendingDonation || $pendingDonation['campaign_id'] !== $campaign->id) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'Session expired. Please try again.');
        }

        // Simulate payment processing delay
        sleep(2);

        // Simulate payment success (90% success rate for demo purposes)
        $paymentSuccess = rand(1, 100) <= 90;

        if ($paymentSuccess) {
            // Process the donation
            $donationData = $pendingDonation['data'];
            $donationData['payment_method'] = 'bkash';

            $donation = $this->donationService->processDonation(
                $campaign,
                auth()->user(),
                $donationData
            );

            // Clear session data
            session()->forget('pending_donation');

            return redirect()->route('bkash.success', $donation);
        } else {
            return redirect()->route('bkash.failed', $campaign)
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    /**
     * Show payment success page
     */
    public function success($donationId)
    {
        $donation = \App\Models\Donation::findOrFail($donationId);
        $this->authorize('view', $donation);

        return view('payments.bkash-success', compact('donation'));
    }

    /**
     * Show payment failed page
     */
    public function failed(Campaign $campaign)
    {
        return view('payments.bkash-failed', compact('campaign'));
    }
}
