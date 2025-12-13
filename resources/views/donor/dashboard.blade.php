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
            @if($donations->count() > 0)
            <a href="{{ route('donation-history.pdf') }}" class="btn btn-outline" style="font-size: 0.875rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                </svg>
                Download PDF
            </a>
            @endif
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