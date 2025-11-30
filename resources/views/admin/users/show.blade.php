@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
<div class="admin-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700;">User Details</h1>
    </div>
</div>

<div class="container">
    <div class="grid grid-cols-3">
        <!-- User Info Card -->
        <div style="grid-column: span 1;">
            <div class="section-card">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 100px; height: 100px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700; margin: 0 auto 1rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $user->name }}</h2>
                    <p style="color: var(--gray-600);">{{ $user->email }}</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <small style="color: var(--gray-600);">Role</small>
                        <div>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">👑 Admin</span>
                            @elseif($user->role === 'recipient')
                                <span class="badge badge-info">📢 Recipient</span>
                            @else
                                <span class="badge badge-secondary">💝 Donor</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <small style="color: var(--gray-600);">Verification Status</small>
                        <div>
                            @if($user->verification_status === 'verified')
                                <span class="badge badge-success">✓ Verified</span>
                            @elseif($user->verification_status === 'pending')
                                <span class="badge badge-warning">⏳ Pending</span>
                            @else
                                <span class="badge badge-secondary">Unverified</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <small style="color: var(--gray-600);">Account Status</small>
                        <div>
                            @if($user->account_status === 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Suspended</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <small style="color: var(--gray-600);">Member Since</small>
                        <strong style="display: block;">{{ $user->created_at->format('M d, Y') }}</strong>
                    </div>
                </div>

                <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--gray-200);">

                <!-- Actions -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @if($user->account_status === 'active')
                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning" style="width: 100%;" onclick="return confirm('Suspend this user?')">🚫 Suspend Account</button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" style="width: 100%;">✓ Activate Account</button>
                        </form>
                    @endif

                    <button class="btn btn-outline" onclick="showRoleModal()" style="width: 100%;">👤 Change Role</button>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">🗑️ Delete User</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Activity Details -->
        <div style="grid-column: span 2;">
            @if($user->isRecipient())
                <!-- Campaigns -->
                <div class="section-card mb-4">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">📢 Campaigns ({{ $user->campaigns->count() }})</h3>
                    @if($user->campaigns->count() > 0)
                        @foreach($user->campaigns as $campaign)
                            <div style="padding: 1rem; background: var(--gray-50); border-radius: 0.5rem; margin-bottom: 0.75rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <strong>{{ $campaign->title }}</strong>
                                    <span class="badge badge-{{ $campaign->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($campaign->status) }}</span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-600);">
                                    ${{ number_format($campaign->current_amount, 0) }} raised of ${{ number_format($campaign->goal_amount, 0) }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--gray-600);">No campaigns created</p>
                    @endif
                </div>
            @endif

            @if($user->isDonor())
                <!-- Donations -->
                <div class="section-card">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">💝 Donations ({{ $user->donations->count() }})</h3>
                    @if($user->donations->count() > 0)
                        <div class="table">
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Campaign</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->donations->take(10) as $donation)
                                    <tr>
                                        <td>{{ Str::limit($donation->campaign->title, 40) }}</td>
                                        <td><strong>${{ number_format($donation->amount, 2) }}</strong></td>
                                        <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                        <td><span class="badge badge-success">Completed</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: 0.5rem; text-align: center;">
                            <strong>Total Donated:</strong> ${{ number_format($user->totalDonated(), 2) }}
                        </div>
                    @else
                        <p style="color: var(--gray-600);">No donations made</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div id="roleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 1rem; max-width: 400px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Change User Role</h3>
        <form action="{{ route('admin.users.change-role', $user) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Select New Role</label>
                <select name="role" class="form-control" required>
                    <option value="donor" {{ $user->role === 'donor' ? 'selected' : '' }}>Donor</option>
                    <option value="recipient" {{ $user->role === 'recipient' ? 'selected' : '' }}>Recipient</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Update Role</button>
                <button type="button" class="btn btn-outline" onclick="closeRoleModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRoleModal() {
    document.getElementById('roleModal').style.display = 'flex';
}

function closeRoleModal() {
    document.getElementById('roleModal').style.display = 'none';
}
</script>
@endsection