<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\DonationService;
use Illuminate\Http\Request;

class NagadPaymentController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    /**
     * Show Nagad payment page
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

        return view('payments.nagad', compact('campaign', 'amount'));
    }

    /**
     * Process Nagad payment (simulation)
     */
    public function process(Request $request, Campaign $campaign)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^01[0-9]{9}$/',
            'pin' => 'required|string|size:4',
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
            $donationData['payment_method'] = 'nagad';

            $donation = $this->donationService->processDonation(
                $campaign,
                auth()->user(),
                $donationData
            );

            // Clear session data
            session()->forget('pending_donation');

            return redirect()->route('nagad.success', $donation);
        } else {
            return redirect()->route('nagad.failed', $campaign)
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

        return view('payments.nagad-success', compact('donation'));
    }

    /**
     * Show payment failed page
     */
    public function failed(Campaign $campaign)
    {
        return view('payments.nagad-failed', compact('campaign'));
    }
}
