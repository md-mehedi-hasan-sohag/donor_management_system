<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Services\DonationService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:5',
            'donation_type' => 'required|in:monetary,in_kind',
            'payment_method' => 'nullable|in:bkash,nagad,card',
            'is_anonymous' => 'boolean',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly',
            'message' => 'nullable|string|max:500',
            'in_kind_items' => 'nullable|string',
        ]);

        // Store donation data in session for payment processing
        session([
            'pending_donation' => [
                'campaign_id' => $campaign->id,
                'user_id' => auth()->id(),
                'data' => $validated,
            ]
        ]);

        // If payment method is bKash, redirect to bKash payment page
        if (($validated['payment_method'] ?? 'card') === 'bkash') {
            return redirect()->route('bkash.payment', $campaign);
        }

        // If payment method is Nagad, redirect to Nagad payment page
        if (($validated['payment_method'] ?? 'card') === 'nagad') {
            return redirect()->route('nagad.payment', $campaign);
        }

        // For card or in-kind donations, process directly
        $validated['payment_method'] = $validated['payment_method'] ?? 'card';
        $donation = $this->donationService->processDonation(
            $campaign,
            auth()->user(),
            $validated
        );

        // Clear session data
        session()->forget('pending_donation');

        return redirect()->route('donations.receipt', $donation)
            ->with('success', 'Thank you for your donation!');
    }

    public function receipt(Donation $donation)
    {
        $this->authorize('view', $donation);

        return view('donations.receipt', compact('donation'));
    }
}