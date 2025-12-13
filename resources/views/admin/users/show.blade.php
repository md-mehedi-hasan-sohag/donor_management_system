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
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">💝 Donations ({{ $user->donations->count() }})</h3>
                        @if($user->donations->count() > 0)
                        <a href="{{ route('admin.users.donation-history.pdf', $user) }}" class="btn btn-outline" style="font-size: 0.875rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                            </svg>
                            Download PDF
                        </a>
                        @endif
                    </div>
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