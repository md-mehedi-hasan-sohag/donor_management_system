<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Support\Str;

class DonationService
{
    /**
     * Process a new donation
     */
    public function processDonation(Campaign $campaign, User $user, array $data): Donation
    {
        $platformFee = $this->calculatePlatformFee($data['amount']);
        $netAmount = $data['amount'] - $platformFee;

        $donation = new Donation($data);
        $donation->campaign_id = $campaign->id;
        $donation->user_id = $user->id;
        $donation->donor_name = $user->name;
        $donation->currency = $user->preferred_currency;
        $donation->platform_fee = $platformFee;
        $donation->net_amount = $netAmount;
        $donation->transaction_id = $this->generateTransactionId();
        $donation->payment_method = $data['payment_method'] ?? 'card';
        $donation->payment_status = 'completed'; // Simulate successful payment
        $donation->payment_completed_at = now();

        if ($data['is_recurring'] ?? false) {
            $donation->next_recurring_date = $this->calculateNextRecurringDate($data['recurring_frequency']);
            $donation->recurring_active = true;
        }

        $donation->save();

        // Update campaign statistics
        $this->updateCampaignStats($campaign, $netAmount);

        // Check and create milestone updates
        $this->checkMilestones($campaign);

        return $donation;
    }

    /**
     * Calculate platform fee based on donation amount
     */
    private function calculatePlatformFee(float $amount): float
    {
        $platformFeePercentage = PlatformSetting::get('platform_fee_percentage', 2.5);
        return $amount * ($platformFeePercentage / 100);
    }

    /**
     * Generate unique transaction ID
     */
    private function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(Str::random(12));
    }

    /**
     * Calculate next recurring donation date
     */
    private function calculateNextRecurringDate(?string $frequency)
    {
        if (!$frequency) {
            return null;
        }

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

    /**
     * Update campaign statistics after donation
     */
    private function updateCampaignStats(Campaign $campaign, float $netAmount): void
    {
        $campaign->increment('current_amount', $netAmount);
        $campaign->increment('total_donors');
    }

    /**
     * Check and create milestone updates for campaign
     */
    private function checkMilestones(Campaign $campaign): void
    {
        $progress = $campaign->progressPercentage();
        $milestones = [25, 50, 75, 100];

        foreach ($milestones as $milestone) {
            if ($progress >= $milestone) {
                $this->createMilestoneUpdate($campaign, $milestone);
            }
        }
    }

    /**
     * Create milestone update if it doesn't exist
     */
    private function createMilestoneUpdate(Campaign $campaign, int $milestone): void
    {
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

    /**
     * Get donation receipt data
     */
    public function getDonationReceipt(Donation $donation): array
    {
        return [
            'donation' => $donation->load('campaign', 'user'),
            'tax_deductible' => $this->isTaxDeductible($donation),
            'receipt_number' => $this->generateReceiptNumber($donation),
        ];
    }

    /**
     * Check if donation is tax deductible
     */
    private function isTaxDeductible(Donation $donation): bool
    {
        // Logic to determine if donation is tax deductible
        // This depends on campaign type, organization status, etc.
        return true; // Simplified for now
    }

    /**
     * Generate receipt number
     */
    private function generateReceiptNumber(Donation $donation): string
    {
        return 'RCPT-' . $donation->id . '-' . date('Y');
    }
}
