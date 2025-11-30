@extends('layouts.app')

@section('title', 'Review Verification')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 2rem;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.verifications.index') }}" style="color: var(--primary-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
            ← Back to Verifications
        </a>
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Review Verification Request</h1>
        <p style="color: var(--gray-600);">Submitted on {{ $verification->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--gray-200);">
            Applicant Information
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Full Name</label>
                <p style="color: var(--gray-900);">{{ $verification->user->name }}</p>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Email</label>
                <p style="color: var(--gray-900);">{{ $verification->user->email }}</p>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Application Type</label>
            <span style="display: inline-block; padding: 0.5rem 1rem; background: #dbeafe; color: #1e40af; border-radius: 0.375rem; font-weight: 500;">
                {{ ucfirst($verification->recipient_type) }}
            </span>
        </div>
    </div>

    @if($verification->recipient_type === 'individual')
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--gray-200);">
                Individual Verification Documents
            </h2>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Government-Issued Photo ID</label>
                @if($verification->government_id_path)
                    <a href="{{ asset('storage/' . $verification->government_id_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        📄 View Document
                    </a>
                @else
                    <p style="color: var(--gray-500);">Not provided</p>
                @endif
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Proof of Address</label>
                @if($verification->proof_of_address_path)
                    <a href="{{ asset('storage/' . $verification->proof_of_address_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        📄 View Document
                    </a>
                @else
                    <p style="color: var(--gray-500);">Not provided</p>
                @endif
            </div>
        </div>
    @else
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--gray-200);">
                Organization Verification Documents
            </h2>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Organization Name</label>
                <p style="color: var(--gray-900);">{{ $verification->organization_name }}</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Legal Registration Documents</label>
                @if($verification->registration_documents_path)
                    <a href="{{ asset('storage/' . $verification->registration_documents_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        📄 View Document
                    </a>
                @else
                    <p style="color: var(--gray-500);">Not provided</p>
                @endif
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Tax-Exempt Status</label>
                @if($verification->tax_exempt_status_path)
                    <a href="{{ asset('storage/' . $verification->tax_exempt_status_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        📄 View Document
                    </a>
                @else
                    <p style="color: var(--gray-500);">Not provided</p>
                @endif
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Primary Contact Name</label>
                <p style="color: var(--gray-900);">{{ $verification->primary_contact_name }}</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Primary Contact ID</label>
                @if($verification->primary_contact_id_path)
                    <a href="{{ asset('storage/' . $verification->primary_contact_id_path) }}" target="_blank" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        📄 View Document
                    </a>
                @else
                    <p style="color: var(--gray-500);">Not provided</p>
                @endif
            </div>
        </div>
    @endif

    @if($verification->status === 'pending')
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">Review Decision</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem; font-size: 1rem; background: #10b981; border-color: #10b981;">
                        ✅ Approve Verification
                    </button>
                </form>

                <button type="button" onclick="document.getElementById('rejectForm').style.display='block'" class="btn btn-danger" style="width: 100%; padding: 1rem; font-size: 1rem;">
                    ❌ Reject Verification
                </button>
            </div>

            <form id="rejectForm" action="{{ route('admin.verifications.reject', $verification) }}" method="POST" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                @csrf
                <div class="form-group">
                    <label class="form-label">Rejection Reason *</label>
                    <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Please provide a clear reason for rejection..."></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-danger" style="flex: 1;">Confirm Rejection</button>
                    <button type="button" onclick="document.getElementById('rejectForm').style.display='none'" class="btn btn-secondary" style="flex: 1;">Cancel</button>
                </div>
            </form>
        </div>
    @elseif($verification->status === 'approved')
        <div class="alert alert-success">
            ✅ This verification was approved by {{ $verification->reviewer->name }} on {{ $verification->reviewed_at->format('F d, Y \a\t h:i A') }}
        </div>
    @elseif($verification->status === 'rejected')
        <div class="alert alert-error">
            ❌ This verification was rejected by {{ $verification->reviewer->name }} on {{ $verification->reviewed_at->format('F d, Y \a\t h:i A') }}
        </div>
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-top: 1rem;">
            <label style="display: block; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem;">Rejection Reason</label>
            <p style="color: var(--gray-900);">{{ $verification->rejection_reason }}</p>
        </div>
    @endif
</div>
@endsection
