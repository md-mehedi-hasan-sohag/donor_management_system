@extends('layouts.app')

@section('title', 'Pending Campaigns - Admin')

@section('content')
<div class="admin-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700;">📋 Pending Campaign Approvals</h1>
        <p style="opacity: 0.9;">Review and approve campaigns awaiting publication</p>
    </div>
</div>

<div class="container">
    @if($pendingCampaigns->count() > 0)
        @foreach($pendingCampaigns as $campaign)
            <div class="section-card mb-4">
                <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem;">
                    <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/200x150?text=No+Image' }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 0.5rem;">
                    
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $campaign->title }}</h2>
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                                    <span class="badge badge-info">{{ $campaign->category->name }}</span>
                                    @if($campaign->is_urgent)
                                        <span class="badge badge-urgent">🔥 Urgent</span>
                                    @endif
                                    @if($campaign->accepts_volunteers)
                                        <span class="badge badge-secondary">👋 Volunteers</span>
                                    @endif
                                    @if($campaign->accepts_in_kind)
                                        <span class="badge badge-secondary">📦 In-Kind</span>
                                    @endif
                                </div>
                            </div>
                            <span style="color: var(--gray-500); font-size: 0.875rem;">Submitted {{ $campaign->created_at->diffForHumans() }}</span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <small style="color: var(--gray-600);">Creator</small>
                                <strong style="display: block;">{{ $campaign->user->name }}</strong>
                            </div>
                            <div>
                                <small style="color: var(--gray-600);">Goal</small>
                                <strong style="display: block;">${{ number_format($campaign->goal_amount, 0) }}</strong>
                            </div>
                            <div>
                                <small style="color: var(--gray-600);">Duration</small>
                                <strong style="display: block;">{{ now()->diffInDays($campaign->end_date) }} days</strong>
                            </div>
                            <div>
                                <small style="color: var(--gray-600);">Location</small>
                                <strong style="display: block;">{{ Str::limit($campaign->location, 20) }}</strong>
                            </div>
                        </div>

                        <p style="color: var(--gray-700); margin-bottom: 1rem;">{{ Str::limit($campaign->description, 200) }}</p>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info" target="_blank">👁️ View Full Campaign</a>
                            
                            <form action="{{ route('admin.campaigns.approve', $campaign) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this campaign?')">
                                @csrf
                                <button type="submit" class="btn btn-success">✓ Approve</button>
                            </form>

                            <button class="btn btn-danger" onclick="showRejectModal({{ $campaign->id }}, '{{ $campaign->title }}')">✗ Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="section-card" style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">All Caught Up!</h2>
            <p style="color: var(--gray-600);">There are no campaigns pending approval at this time.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 1rem; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Reject Campaign</h3>
        <p id="rejectCampaignTitle" style="color: var(--gray-600); margin-bottom: 1rem;"></p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Rejection Reason *</label>
                <textarea name="rejection_reason" class="form-control" required rows="4" placeholder="Provide a clear reason for rejection..."></textarea>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-danger">Reject Campaign</button>
                <button type="button" class="btn btn-outline" onclick="closeRejectModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(campaignId, title) {
    document.getElementById('rejectModal').style.display = 'flex';
    document.getElementById('rejectForm').action = `/admin/campaigns/${campaignId}/reject`;
    document.getElementById('rejectCampaignTitle').textContent = `Campaign: "${title}"`;
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endsection