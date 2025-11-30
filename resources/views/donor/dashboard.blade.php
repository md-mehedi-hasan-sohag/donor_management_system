@extends('layouts.app')

@section('title', 'Donor Dashboard - DonorLink')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .badge-display {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .badge-item {
        text-align: center;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 0.5rem;
        min-width: 100px;
    }

    .badge-icon {
        font-size: 3rem;
        margin-bottom: 0.5rem;
    }
</style>

<div class="dashboard-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p style="opacity: 0.9;">Track your donations and discover new campaigns to support</p>
    </div>
</div>

<div class="container">
    <!-- Stats Overview -->
    <div class="grid grid-cols-4 mb-4">
        <div class="stat-box">
            <div class="stat-value">${{ number_format($stats['total_donated'], 2) }}</div>
            <div class="stat-label">Total Donated</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $stats['campaigns_supported'] }}</div>
            <div class="stat-label">Campaigns Supported</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $stats['recurring_donations'] }}</div>
            <div class="stat-label">Recurring Donations</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $stats['badges_earned'] }}</div>
            <div class="stat-label">Badges Earned</div>
        </div>
    </div>

    <!-- Badges Earned -->
    @if($badges->count() > 0)
    <div class="section-card mb-4">
        <div class="section-header">
            <h2 class="section-title">🏆 Your Badges</h2>
        </div>
        <div class="badge-display">
            @foreach($badges as $badge)
                <div class="badge-item">
                    <div class="badge-icon">🏆</div>
                    <strong style="display: block; margin-bottom: 0.25rem;">{{ $badge->name }}</strong>
                    <p style="font-size: 0.875rem; color: var(--gray-600);">{{ $badge->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recommended Campaigns -->
    <div class="section-card mb-4">
        <div class="section-header">
            <h2 class="section-title">✨ Recommended for You</h2>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline" style="font-size: 0.875rem;">Browse All</a>
        </div>
        <div class="grid grid-cols-3">
            @foreach($recommendedCampaigns as $campaign)
                <div class="card">
                    @if($campaign->is_verified)
                        <span class="badge badge-verified" style="position: absolute; top: 1rem; left: 1rem; z-index: 10;">✓</span>
                    @endif
                    <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/400x200?text=Campaign' }}" alt="{{ $campaign->title }}" class="card-img">
                    <div class="card-body">
                        <h3 class="card-title">{{ Str::limit($campaign->title, 50) }}</h3>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem;">
                            <span><strong>${{ number_format($campaign->current_amount, 0) }}</strong> raised</span>
                            <span><strong>{{ $campaign->daysRemaining() }}</strong> days</span>
                        </div>
                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary" style="width: 100%;">View Campaign</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Followed Campaigns -->
    @if($followedCampaigns->count() > 0)
    <div class="section-card mb-4">
        <div class="section-header">
            <h2 class="section-title">❤️ Campaigns You Follow</h2>
        </div>
        <div class="grid grid-cols-3">
            @foreach($followedCampaigns as $campaign)
                <div class="card">
                    @if($campaign->is_urgent)
                        <span class="badge badge-urgent" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">🔥</span>
                    @endif
                    <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/400x200?text=Campaign' }}" alt="{{ $campaign->title }}" class="card-img">
                    <div class="card-body">
                        <h3 class="card-title">{{ Str::limit($campaign->title, 50) }}</h3>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                        </div>
                        <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem;">
                            ${{ number_format($campaign->current_amount, 0) }} of ${{ number_format($campaign->goal_amount, 0) }}
                        </p>
                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary" style="width: 100%;">View Updates</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Donation History -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">📊 Your Donation History</h2>
        </div>
        @if($donations->count() > 0)
            <div class="table">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                        <tr>
                            <td>
                                <a href="{{ route('campaigns.show', $donation->campaign) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ Str::limit($donation->campaign->title, 40) }}
                                </a>
                            </td>
                            <td>
                                @if($donation->is_recurring)
                                    <span class="badge badge-info">🔄 Recurring</span>
                                @else
                                    <span class="badge badge-secondary">One-time</span>
                                @endif
                            </td>
                            <td><strong>${{ number_format($donation->amount, 2) }}</strong></td>
                            <td>
                                @if($donation->payment_status === 'completed')
                                    <span class="badge badge-success">✓ Completed</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($donation->payment_status) }}</span>
                                @endif
                            </td>
                            <td>{{ $donation->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('donations.receipt', $donation) }}" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.5rem 1rem;">View Receipt</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: var(--gray-500);">
                <p style="font-size: 1.25rem; margin-bottom: 1rem;">You haven't made any donations yet</p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary">Explore Campaigns</a>
            </div>
        @endif
    </div>
</div>
@endsection