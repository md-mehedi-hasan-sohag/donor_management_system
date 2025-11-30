@extends('layouts.app')

@section('title', 'User Management - Admin')

@section('content')
<div class="admin-header">
    <div class="container">
        <h1 style="font-size: 2rem; font-weight: 700;">👥 User Management</h1>
        <p style="opacity: 0.9;">Manage all platform users</p>
    </div>
</div>

<div class="container">
    <!-- Filters -->
    <div class="section-card mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-cols-4 gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    <option value="donor" {{ request('role') == 'donor' ? 'selected' : '' }}>Donors</option>
                    <option value="recipient" {{ request('role') == 'recipient' ? 'selected' : '' }}>Recipients</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                </select>

                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>

                <button type="submit" class="btn btn-primary">🔍 Search</button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="section-card">
        <div class="table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Verification</th>
                        <th>Account Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <strong style="display: block;">{{ $user->name }}</strong>
                            <small style="color: var(--gray-600);">{{ $user->email }}</small>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">👑 Admin</span>
                            @elseif($user->role === 'recipient')
                                <span class="badge badge-info">📢 Recipient</span>
                            @else
                                <span class="badge badge-secondary">💝 Donor</span>
                            @endif
                        </td>
                        <td>
                            @if($user->verification_status === 'verified')
                                <span class="badge badge-success">✓ Verified</span>
                            @elseif($user->verification_status === 'pending')
                                <span class="badge badge-warning">⏳ Pending</span>
                            @else
                                <span class="badge badge-secondary">Unverified</span>
                            @endif
                        </td>
                        <td>
                            @if($user->account_status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($user->account_status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($user->account_status) }}</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info action-btn">View</a>
                            
                            @if($user->account_status === 'active')
                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning action-btn" onclick="return confirm('Suspend this user?')">Suspend</button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success action-btn">Activate</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection