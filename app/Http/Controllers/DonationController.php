<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
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

        $platformFeePercentage = PlatformSetting::get('platform_fee_percentage', 2.5);
        $platformFee = $validated['amount'] * ($platformFeePercentage / 100);
        $netAmount = $validated['amount'] - $platformFee;

        $donation = new Donation($validated);
        $donation->campaign_id = $campaign->id;
        $donation->user_id = auth()->id();
        $donation->donor_name = auth()->user()->name;
        $donation->currency = auth()->user()->preferred_currency;
        $donation->platform_fee = $platformFee;
        $donation->net_amount = $netAmount;
        $donation->transaction_id = 'TXN-' . strtoupper(Str::random(12));
        $donation->payment_method = 'stripe'; // In real app, this comes from payment gateway
        $donation->payment_status = 'completed'; // Simulate successful payment
        $donation->payment_completed_at = now();

        if ($validated['is_recurring'] ?? false) {
            $donation->next_recurring_date = $this->calculateNextRecurringDate($validated['recurring_frequency']);
            $donation->recurring_active = true;
        }

        $donation->save();

        // Update campaign
        $campaign->increment('current_amount', $netAmount);
        $campaign->increment('total_donors');

        // Check for milestones
        $this->checkMilestones($campaign);

        return redirect()->route('donations.receipt', $donation)
            ->with('success', 'Thank you for your donation!');
    }

    public function receipt(Donation $donation)
    {
        $this->authorize('view', $donation);

        return view('donations.receipt', compact('donation'));
    }

    private function calculateNextRecurringDate($frequency)
    {
        switch ($frequency) {
            case 'weekly':
                return now()->addWeek();
            case 'monthly':
                return now()->addMonth();
            case 'quarterly':
                return now()->addMonths(3);
            default:
                return null;
        }
    }

    private function checkMilestones(Campaign $campaign)
    {
        $progress = $campaign->progressPercentage();
        $milestones = [25, 50, 75, 100];

        foreach ($milestones as $milestone) {
            if ($progress >= $milestone) {
                $exists = $campaign->updates()
                    ->where('milestone_percentage', $milestone)
                    ->exists();

                if (!$exists) {
                    $campaign->updates()->create([
                        'update_type' => 'milestone',
                        'title' => "{$milestone}% Milestone Reached!",
                        'content' => "Amazing! This campaign has reached {$milestone}% of its goal. Thank you to all supporters!",
                        'milestone_percentage' => $milestone,
                    ]);
                }
            }
        }
    }
}