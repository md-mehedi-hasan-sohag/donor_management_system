@extends('layouts.app')

@section('title', 'Pending Verifications')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Recipient Verifications</h1>
        <p style="color: var(--gray-600);">Review and approve recipient verification requests</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($pendingVerifications->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📋</div>
            <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--gray-900);">No Pending Verifications</h2>
            <p style="color: var(--gray-600);">All verification requests have been processed.</p>
        </div>
    @else
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Recipient</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Type</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Organization</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Submitted</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingVerifications as $verification)
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600;">{{ $verification->user->name }}</div>
                                <div style="font-size: 0.875rem; color: var(--gray-600);">{{ $verification->user->email }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                    {{ ucfirst($verification->recipient_type) }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                {{ $verification->organization_name ?? 'N/A' }}
                            </td>
                            <td style="padding: 1rem; color: var(--gray-600);">
                                {{ $verification->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 1rem;">
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                    Pending
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="{{ route('admin.verifications.show', $verification) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
