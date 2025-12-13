<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationHistoryController extends Controller
{
    /**
     * Download donation history PDF for a donor
     * Accessible by the donor themselves or admin
     */
    public function downloadDonorHistory($userId = null)
    {
        $user = auth()->user();

        // If no userId provided, use authenticated user
        if ($userId === null) {
            $donor = $user;
        } else {
            // Only admin can view other users' donation history
            if (!$user->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            $donor = User::findOrFail($userId);
        }

        // Get all donations for this donor
        $donations = Donation::where('user_id', $donor->id)
            ->with('campaign')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalDonated = $donations->where('payment_status', 'completed')->sum('amount');
        $totalDonations = $donations->where('payment_status', 'completed')->count();
        $campaignsSupported = $donations->where('payment_status', 'completed')
            ->pluck('campaign_id')
            ->unique()
            ->count();
        $recurringDonations = $donations->where('is_recurring', true)
            ->where('recurring_active', true)
            ->count();

        $data = [
            'donor' => $donor,
            'donations' => $donations,
            'totalDonated' => $totalDonated,
            'totalDonations' => $totalDonations,
            'campaignsSupported' => $campaignsSupported,
            'recurringDonations' => $recurringDonations,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('pdfs.donor-history', $data);

        return $pdf->download('donation-history-' . $donor->name . '-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download donation history PDF for a campaign
     * Accessible by the campaign owner (recipient) or admin
     */
    public function downloadCampaignHistory($campaignId)
    {
        $user = auth()->user();
        $campaign = Campaign::with('user')->findOrFail($campaignId);

        // Check authorization: must be campaign owner or admin
        if (!$user->isAdmin() && $campaign->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get all donations for this campaign
        $donations = Donation::where('campaign_id', $campaign->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalReceived = $donations->where('payment_status', 'completed')->sum('net_amount');
        $totalDonations = $donations->where('payment_status', 'completed')->count();
        $uniqueDonors = $donations->where('payment_status', 'completed')
            ->pluck('user_id')
            ->unique()
            ->count();
        $averageDonation = $totalDonations > 0
            ? $totalReceived / $totalDonations
            : 0;
        $monetaryDonations = $donations->where('donation_type', 'monetary')
            ->where('payment_status', 'completed')
            ->count();
        $inKindDonations = $donations->where('donation_type', 'in_kind')
            ->where('payment_status', 'completed')
            ->count();

        $data = [
            'campaign' => $campaign,
            'donations' => $donations,
            'totalReceived' => $totalReceived,
            'totalDonations' => $totalDonations,
            'uniqueDonors' => $uniqueDonors,
            'averageDonation' => $averageDonation,
            'monetaryDonations' => $monetaryDonations,
            'inKindDonations' => $inKindDonations,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('pdfs.campaign-history', $data);

        return $pdf->download('campaign-donations-' . $campaign->slug . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
