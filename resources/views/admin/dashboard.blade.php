@extends('layouts.app')

@section('title', 'Admin Dashboard - DonorLink')

@section('content')
<style>
    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .quick-stat {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary);
    }

    .quick-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }

    .quick-stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .section-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-200);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
    }
</style>

<div class="admin-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Admin Dashboard</h1>
        <p style="opacity: 0.9;">Manage and oversee all platform operations</p>
    </div>
</div>

<div class="container">
    <!-- Quick Stats -->
    <div class="grid grid-cols-4 mb-4">
        <div class="quick-stat">
            <div class="quick-stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="quick-stat-label">Total Users</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--success);">
            <div class="quick-stat-value" style="color: var(--success);">{{ number_format($stats['active_campaigns']) }}</div>
            <div class="quick-stat-label">Active Campaigns</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--warning);">
            <div class="quick-stat-value" style="color: var(--warning);">{{ number_format($stats['pending_campaigns']) }}</div>
            <div class="quick-stat-label">Pending Approvals</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--info);">
            <div class="quick-stat-value" style="color: var(--info);">${{ number_format($stats['total_donations'], 0) }}</div>
            <div class="quick-stat-label">Total Donations</div>
        </div>
    </div>

    <div class="grid grid-cols-4 mb-4">
        <div class="quick-stat" style="border-left-color: var(--secondary);">
            <div class="quick-stat-value" style="color: var(--secondary);">{{ number_format($stats['total_donors']) }}</div>
            <div class="quick-stat-label">Total Donors</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--success);">
            <div class="quick-stat-value" style="color: var(--success);">{{ number_format($stats['verified_recipients']) }}</div>
            <div class="quick-stat-label">Verified Recipients</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--warning);">
            <div class="quick-stat-value" style="color: var(--warning);">{{ number_format($stats['pending_verifications']) }}</div>
            <div class="quick-stat-label">Pending Verifications</div>
        </div>
        <div class="quick-stat" style="border-left-color: var(--danger);">
            <div class="quick-stat-value" style="color: var(--danger);">{{ number_format($stats['total_campaigns']) }}</div>
            <div class="quick-stat-label">Total Campaigns</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.campaigns.pending') }}" class="btn btn-warning">
                📋 Review Campaigns ({{ $stats['pending_campaigns'] }})
            </a>
            <a href="{{ route('admin.verifications.index') }}" class="btn btn-info">
                ✓ Verify Recipients ({{ $stats['pending_verifications'] }})
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                👥 Manage Users
            </a>
            <a href="{{ route('admin.analytics') }}" class="btn btn-secondary">
                📊 View Analytics
            </a>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline">
                ⚙️ Settings
            </a>
        </div>
    </div>

    <!-- Pending Verifications -->
    @if($pendingVerifications->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">✓ Pending Recipient Verifications</h2>
            <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline" style="font-size: 0.875rem;">View All</a>
        </div>

        <div class="table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Organization</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingVerifications as $verification)
                    <tr>
                        <td><strong>{{ $verification->user->name }}</strong></td>
                        <td>{{ $verification->user->email }}</td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($verification->recipient_type) }}</span>
                        </td>
                        <td>{{ $verification->organization_name ?? 'N/A' }}</td>
                        <td>{{ $verification->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.verifications.show', $verification) }}" class="btn btn-primary action-btn">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pending Campaign Approvals -->
    @if($pendingCampaigns->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">⏳ Pending Campaign Approvals</h2>
            <a href="{{ route('admin.campaigns.pending') }}" class="btn btn-outline" style="font-size: 0.875rem;">View All</a>
        </div>
        
        <div class="table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Creator</th>
                        <th>Category</th>
                        <th>Goal Amount</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingCampaigns->take(5) as $campaign)
                    <tr>
                        <td>
                            <strong>{{ $campaign->title }}</strong>
                            @if($campaign->is_urgent)
                                <span class="badge badge-urgent" style="font-size: 0.75rem; margin-left: 0.5rem;">🔥 Urgent</span>
                            @endif
                        </td>
                        <td>{{ $campaign->user->name }}</td>
                        <td>{{ $campaign->category->name }}</td>
                        <td>${{ number_format($campaign->goal_amount, 2) }}</td>
                        <td>{{ $campaign->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info action-btn" target="_blank">View</a>
                            <form action="{{ route('admin.campaigns.approve', $campaign) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success action-btn" onclick="return confirm('Approve this campaign?')">✓ Approve</button>
                            </form>
                            <button class="btn btn-danger action-btn" onclick="showRejectModal({{ $campaign->id }})">✗ Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Donations -->
    <div class="section-card">
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
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDonations->take(10) as $donation)
                    <tr>
                        <td>
                            @if($donation->is_anonymous)
                                Anonymous
                            @else
                                {{ $donation->user ? $donation->user->name : $donation->donor_name }}
                            @endif
                        </td>
                        <td>{{ Str::limit($donation->campaign->title, 40) }}</td>
                        <td>
                            @if($donation->donation_type === 'monetary')
                                <strong>${{ number_format($donation->amount, 2) }}</strong>
                            @else
                                In-Kind
                            @endif
                        </td>
                        <td>
                            @if($donation->is_recurring)
                                <span class="badge badge-info">🔄 Recurring</span>
                            @else
                                <span class="badge badge-secondary">One-time</span>
                            @endif
                        </td>
                        <td>{{ $donation->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($donation->payment_status === 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($donation->payment_status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($donation->payment_status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">📊 Recent Campaigns</h2>
        </div>
        
        <div class="table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Creator</th>
                        <th>Category</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentCampaigns as $campaign)
                    <tr>
                        <td>
                            <strong>{{ Str::limit($campaign->title, 40) }}</strong>
                            @if($campaign->is_verified)
                                <span class="badge badge-verified" style="font-size: 0.75rem; margin-left: 0.5rem;">✓</span>
                            @endif
                        </td>
                        <td>{{ $campaign->user->name }}</td>
                        <td>{{ $campaign->category->name }}</td>
                        <td>
                            <div class="progress" style="height: 0.5rem;">
                                <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                            </div>
                            <small>{{ number_format($campaign->progressPercentage(), 1) }}%</small>
                        </td>
                        <td>
                            @if($campaign->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($campaign->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($campaign->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($campaign->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $campaign->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info action-btn">View</a>
                            @if($campaign->status === 'active' && !$campaign->is_verified)
                                <form action="{{ route('admin.campaigns.verify', $campaign) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success action-btn">Verify</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reject Campaign Modal (Simple implementation) -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 2rem; border-radius: 1rem; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Reject Campaign</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Rejection Reason</label>
                <textarea name="rejection_reason" class="form-control" required placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-danger">Reject Campaign</button>
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectModal(campaignId) {
        document.getElementById('rejectModal').style.display = 'flex';
        document.getElementById('rejectForm').action = `/admin/campaigns/${campaignId}/reject`;
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }
</script>
@endsection