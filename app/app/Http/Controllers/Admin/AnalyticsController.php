<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::active()->count(),
            'total_donations' => Donation::completed()->sum('amount'),
            'total_platform_fees' => Donation::completed()->sum('platform_fee'),
        ];

        // Donations by month (last 12 months)
        $donationsByMonth = Donation::completed()
            ->where('created_at', '>=', now()->subYear())
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Donations by category
        $donationsByCategory = Campaign::select('categories.name', DB::raw('SUM(campaigns.current_amount) as total'))
            ->join('categories', 'campaigns.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Top campaigns
        $topCampaigns = Campaign::active()
            ->orderBy('current_amount', 'desc')
            ->take(10)
            ->get();

        // Top donors
        $topDonors = User::donors()
            ->withCount('donations')
            ->with('donations')
            ->get()
            ->map(function ($user) {
                $user->total_donated = $user->donations->where('payment_status', 'completed')->sum('amount');
                return $user;
            })
            ->sortByDesc('total_donated')
            ->take(10);

        // User growth (last 12 months)
        $userGrowth = User::where('created_at', '>=', now()->subYear())
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', compact(
            'stats',
            'donationsByMonth',
            'donationsByCategory',
            'topCampaigns',
            'topDonors',
            'userGrowth'
        ));
    }
}