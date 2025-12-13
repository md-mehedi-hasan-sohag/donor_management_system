<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\RecipientVerification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isRecipient()) {
            return $this->recipientDashboard();
        } else {
            return $this->donorDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::active()->count(),
            'pending_campaigns' => Campaign::pending()->count(),
            'total_donations' => Donation::completed()->sum('amount'),
            'total_donors' => User::donors()->count(),
            'verified_recipients' => RecipientVerification::approved()->count(),
            'pending_verifications' => RecipientVerification::pending()->count(),
        ];

        $recentCampaigns = Campaign::with('user', 'category')
            ->latest()
            ->take(10)
            ->get();

        $recentDonations = Donation::with('campaign', 'user')
            ->completed()
            ->latest()
            ->take(10)
            ->get();

        $pendingCampaigns = Campaign::pending()
            ->with('user', 'category')
            ->latest()
            ->get();

        $pendingVerifications = RecipientVerification::pending()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCampaigns', 'recentDonations', 'pendingCampaigns', 'pendingVerifications'));
    }

    private function recipientDashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_campaigns' => $user->campaigns()->count(),
            'active_campaigns' => $user->campaigns()->active()->count(),
            'total_raised' => $user->campaigns()->sum('current_amount'),
            'total_donors' => Donation::whereIn('campaign_id', $user->campaigns->pluck('id'))
                ->completed()
                ->distinct('user_id')
                ->count('user_id'),
        ];

        $campaigns = $user->campaigns()
            ->with('category', 'donations')
            ->latest()
            ->get();

        $recentDonations = Donation::whereIn('campaign_id', $user->campaigns->pluck('id'))
            ->with('campaign', 'user')
            ->completed()
            ->latest()
            ->take(10)
            ->get();

        return view('recipient.dashboard', compact('stats', 'campaigns', 'recentDonations'));
    }

    private function donorDashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_donated' => $user->totalDonated(),
            'campaigns_supported' => $user->totalCampaignsSupported(),
            'recurring_donations' => $user->donations()->recurring()->count(),
            'badges_earned' => $user->badges()->count(),
        ];

        $donations = $user->donations()
            ->with('campaign')
            ->latest()
            ->get();

        $followedCampaigns = $user->followedCampaigns()
            ->with('category', 'user')
            ->active()
            ->get();

        $recommendedCampaigns = Campaign::active()
            ->verified()
            ->inRandomOrder()
            ->take(6)
            ->get();

        $badges = $user->badges;

        return view('donor.dashboard', compact('stats', 'donations', 'followedCampaigns', 'recommendedCampaigns', 'badges'));
    }
}