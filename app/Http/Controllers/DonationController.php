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
            'is_anonymous' => 'boolean',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly',
            'message' => 'nullable|string|max:500',
            'in_kind_items' => 'nullable|string',
        ]);

        $donation = $this->donationService->processDonation(
            $campaign,
            auth()->user(),
            $validated
        );

        return redirect()->route('donations.receipt', $donation)
            ->with('success', 'Thank you for your donation!');
    }

    public function receipt(Donation $donation)
    {
        $this->authorize('view', $donation);

        return view('donations.receipt', compact('donation'));
    }
}