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

                        <div class="flex gap-1" style="margin-bottom: 0.5rem;">
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info" style="flex: 1; font-size: 0.875rem;">View</a>
                            @if($campaign->status !== 'rejected')
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline" style="flex: 1; font-size: 0.875rem;">Edit</a>
                            @endif
                        </div>
                        @if($campaign->status === 'active' && $campaign->total_donors > 0)
                        <a href="{{ route('campaign-donation-history.pdf', $campaign) }}" class="btn btn-outline" style="width: 100%; font-size: 0.875rem; margin-bottom: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                            </svg>
                            Download Donation History
                        </a>
                        @endif
                        @if($campaign->status === 'active')
                        <a href="{{ route('campaigns.qr-code', $campaign) }}" class="btn btn-outline" style="width: 100%; font-size: 0.875rem;" download>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                                <path d="M0 0h6v6H0V0zm1 1v4h4V1H1zm0 9h6v6H0v-6zm1 1v4h4v-4H1zm9-10h6v6h-6V0zm1 1v4h4V1h-4z"/>
                                <path d="M2 2h2v2H2V2zm0 9h2v2H2v-2zm9-9h2v2h-2V2zM5 0v2H3V0h2zM3 5V3H0v2h3zm9 0V3h-2v2h2zM0 8h2v2H0V8zm5 0h2v2H5V8zm5 0h2v2h-2V8z"/>
                            </svg>
                            Download QR Code
                        </a>
                        @endif
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