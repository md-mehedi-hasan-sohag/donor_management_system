@extends('layouts.app')

@section('title', 'Recipient Dashboard - DonorLink')

@section('content')
<div class="dashboard-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Recipient Dashboard</h1>
        <p style="opacity: 0.9;">Manage your campaigns and engage with supporters</p>
    </div>
</div>

<div class="container">
    <!-- Verification Status Alert -->
    @if(auth()->user()->verification_status !== 'verified')
        <div class="alert alert-warning mb-4">
            @if(auth()->user()->verification_status === 'pending')
                ⏳ Your verification is pending review. You'll be able to create public campaigns once approved.
            @else
                ⚠️ You need to complete verification before creating campaigns. 
                <a href="{{ route('verification.index') }}" style="color: var(--primary); font-weight: 600;">Complete Verification →</a>
            @endif
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-4 mb-4">
        <div class="stat-box">
            <div class="stat-value">{{ $stats['total_campaigns'] }}</div>
            <div class="stat-label">Total Campaigns</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $stats['active_campaigns'] }}</div>
            <div class="stat-label">Active Campaigns</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">${{ number_format($stats['total_raised'], 2) }}</div>
            <div class="stat-label">Total Raised</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $stats['total_donors'] }}</div>
            <div class="stat-label">Total Donors</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-card mb-4">
        <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->isVerified())
                <a href="{{ route('campaigns.create') }}" class="btn btn-primary">➕ Create New Campaign</a>
            @else
                <a href="{{ route('verification.index') }}" class="btn btn-warning">✓ Complete Verification</a>
            @endif
        </div>
    </div>

    <!-- Recent Donations -->
    @if($recentDonations->count() > 0)
    <div class="section-card mb-4">
        <div class="section-header">
            <h2 class="section-title">💰 Recent Donations</h2>
        </div>
        <div class="table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Campaign</th>
                        <th>Amount</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDonations->take(10) as $donation)
                    <tr>
                        <td>
                            @if($donation->is_anonymous)
                                Anonymous Donor
                            @else
                                {{ $donation->user ? $donation->user->name : $donation->donor_name }}
                            @endif
                        </td>
                        <td>{{ Str::limit($donation->campaign->title, 30) }}</td>
                        <td><strong>${{ number_format($donation->amount, 2) }}</strong></td>
                        <td>{{ $donation->message ? Str::limit($donation->message, 40) : '-' }}</td>
                        <td>{{ $donation->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- My Campaigns -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">📋 My Campaigns</h2>
        </div>

        @if($campaigns->count() > 0)
            <div class="grid grid-cols-2">
                @foreach($campaigns as $campaign)
                <div class="card">
                    <div style="position: relative;">
                        @if($campaign->status === 'pending')
                            <span class="badge badge-warning" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">⏳ Pending</span>
                        @elseif($campaign->status === 'active')
                            <span class="badge badge-success" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">✓ Active</span>
                        @elseif($campaign->status === 'rejected')
                            <span class="badge badge-danger" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">✗ Rejected</span>
                        @endif
                        <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/400x200?text=Campaign' }}" alt="{{ $campaign->title }}" class="card-img">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $campaign->title }}</h3>
                        
                        @if($campaign->status === 'active')
                            <div class="progress mb-2">
                                <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem;">
                                <span><strong>${{ number_format($campaign->current_amount, 0) }}</strong> / ${{ number_format($campaign->goal_amount, 0) }}</span>
                                <span>{{ $campaign->total_donors }} donors</span>
                            </div>
                        @elseif($campaign->status === 'rejected')
                            <div class="alert alert-error" style="font-size: 0.875rem; padding: 0.75rem; margin-bottom: 1rem;">
                                <strong>Rejection Reason:</strong><br>{{ $campaign->rejection_reason }}
                            </div>
                        @endif

                        <div class="flex gap-1">
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info" style="flex: 1; font-size: 0.875rem;">View</a>
                            @if($campaign->status !== 'rejected')
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline" style="flex: 1; font-size: 0.875rem;">Edit</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: var(--gray-500);">
                <p style="font-size: 1.25rem; margin-bottom: 1rem;">You haven't created any campaigns yet</p>
                @if(auth()->user()->isVerified())
                    <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Create Your First Campaign</a>
                @else
                    <a href="{{ route('verification.index') }}" class="btn btn-warning">Complete Verification First</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection