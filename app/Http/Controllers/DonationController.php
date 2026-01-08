<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Currency;
use App\Services\DonationService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    /**
     * Show donation form
     */
    public function create(Campaign $campaign)
    {
        $currencies = Currency::where('is_active', true)
            ->orderByRaw("code = 'BDT' DESC")
            ->get();

        return view('donations.create', compact('campaign', 'currencies'));
    }

    /**
     * Store donation
     */
    public function store(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:5',
            'donation_type' => 'required|in:monetary,in_kind',
            'payment_method' => 'nullable|in:bkash,nagad,card',
            'is_anonymous' => 'sometimes|boolean',
            'is_recurring' => 'sometimes|boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly',
            'message' => 'nullable|string|max:500',
            'in_kind_items' => 'nullable|string',
        ]);

        session([
            'pending_donation' => [
                'campaign_id' => $campaign->id,
                'user_id' => auth()->id(),
                'data' => $validated,
            ]
        ]);

        // Redirect to mobile payments
        if (($validated['payment_method'] ?? 'card') === 'bkash') {
            return redirect()->route('bkash.payment', $campaign);
        }

        if (($validated['payment_method'] ?? 'card') === 'nagad') {
            return redirect()->route('nagad.payment', $campaign);
        }

        // Default payment method
        $validated['payment_method'] = $validated['payment_method'] ?? 'card';

        $donation = $this->donationService->processDonation(
            $campaign,
            auth()->user(),
            $validated
        );

        ReferralRewardController::handleSuccessfulDonation($donation->user_id);

        session()->forget('pending_donation');

        return redirect()
            ->route('donations.receipt', $donation)
            ->with('success', 'Thank you for your donation!');
    }

    /**
     * Donation receipt
     */
    public function receipt(Donation $donation)
    {
        $this->authorize('view', $donation);

        return view('donations.receipt', compact('donation'));
    }
}
