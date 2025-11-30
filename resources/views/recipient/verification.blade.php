@extends('layouts.app')

@section('title', 'Recipient Verification')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Recipient Verification</h1>
        <p style="opacity: 0.9;">Complete your verification to create public campaigns</p>
    </div>

    <div class="form-body">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($verification && $verification->status === 'pending')
            <div class="alert alert-warning">
                ⏳ Your verification is under review. We'll notify you once it's approved (usually within 24-48 hours).
            </div>

            <div style="text-align: center; padding: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem;">⏰</div>
                <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">Verification Pending</h2>
                <p style="color: var(--gray-600); margin-bottom: 2rem;">Our team is reviewing your documents. You'll receive an email notification once approved.</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        @elseif($verification && $verification->status === 'approved')
            <div class="alert alert-success">
                ✅ Your account is verified! You can now create campaigns.
            </div>

            <div style="text-align: center; padding: 3rem;">
                <div style="font-size: 5rem; margin-bottom: 1rem;">✅</div>
                <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">Account Verified!</h2>
                <p style="color: var(--gray-600); margin-bottom: 2rem;">You're all set to start creating campaigns and making an impact.</p>
                <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Create Your First Campaign</a>
            </div>
        @elseif($verification && $verification->status === 'rejected')
            <div class="alert alert-error">
                ❌ Your verification was rejected. Reason: {{ $verification->rejection_reason }}
            </div>
            <p style="margin-top: 1rem; color: var(--gray-600);">Please submit new documents below.</p>
        @endif

        @if(!$verification || $verification->status === 'rejected')
        <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">I am applying as: *</label>
                <select name="recipient_type" class="form-control" id="recipientType" required onchange="toggleFields()">
                    <option value="">Select type</option>
                    <option value="individual" {{ old('recipient_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="organization" {{ old('recipient_type') == 'organization' ? 'selected' : '' }}>Organization / NGO</option>
                </select>
            </div>

            <!-- Individual Fields -->
            <div id="individualFields" style="display: none;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--gray-900);">Individual Verification Documents</h3>

                <div class="form-group">
                    <label class="form-label">Government-Issued Photo ID *</label>
                    <input type="file" name="government_id" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small style="color: var(--gray-600);">Upload your driver's license, passport, or national ID (PDF, JPG, or PNG, max 2MB)</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Proof of Address *</label>
                    <input type="file" name="proof_of_address" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small style="color: var(--gray-600);">Upload a utility bill, bank statement, or government document (PDF, JPG, or PNG, max 2MB)</small>
                </div>
            </div>

            <!-- Organization Fields -->
            <div id="organizationFields" style="display: none;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin: 2rem 0 1rem; color: var(--gray-900);">Organization Verification Documents</h3>

                <div class="form-group">
                    <label class="form-label">Organization Name *</label>
                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}" placeholder="Your Organization Name">
                </div>

                <div class="form-group">
                    <label class="form-label">Legal Registration Documents *</label>
                    <input type="file" name="registration_documents" class="form-control" accept=".pdf">
                    <small style="color: var(--gray-600);">Certificate of incorporation or registration (PDF, max 2MB)</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Tax-Exempt Status (If Applicable)</label>
                    <input type="file" name="tax_exempt_status" class="form-control" accept=".pdf">
                    <small style="color: var(--gray-600);">501(c)(3) or equivalent documentation (PDF, max 2MB)</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Primary Contact Name *</label>
                    <input type="text" name="primary_contact_name" class="form-control" value="{{ old('primary_contact_name') }}" placeholder="Full Name">
                </div>

                <div class="form-group">
                    <label class="form-label">Primary Contact ID *</label>
                    <input type="file" name="primary_contact_id" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small style="color: var(--gray-600);">Government-issued ID of primary contact (PDF, JPG, or PNG, max 2MB)</small>
                </div>
            </div>

            <div style="background: #dbeafe; padding: 1.5rem; border-radius: 0.5rem; margin: 2rem 0; border-left: 4px solid #3b82f6;">
                <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #1e40af;">🔒 Your Privacy</h3>
                <p style="color: #1e40af; font-size: 0.875rem; margin: 0;">
                    All documents are encrypted and securely stored. They will only be reviewed by our verification team and never shared publicly.
                </p>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: start; gap: 0.5rem;">
                    <input type="checkbox" required style="margin-top: 0.25rem;">
                    <span style="font-size: 0.875rem; color: var(--gray-700);">
                        I certify that all information and documents provided are accurate and authentic. I understand that providing false information may result in account termination.
                    </span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">📤 Submit for Verification</button>
        </form>
        @endif
    </div>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('recipientType').value;
    const individualFields = document.getElementById('individualFields');
    const organizationFields = document.getElementById('organizationFields');

    if (type === 'individual') {
        individualFields.style.display = 'block';
        organizationFields.style.display = 'none';

        // Disable organization fields to prevent submission
        organizationFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = true;
        });

        // Enable individual fields
        individualFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = false;
        });
    } else if (type === 'organization') {
        individualFields.style.display = 'none';
        organizationFields.style.display = 'block';

        // Disable individual fields to prevent submission
        individualFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = true;
        });

        // Enable organization fields
        organizationFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = false;
        });
    } else {
        individualFields.style.display = 'none';
        organizationFields.style.display = 'none';

        // Disable all fields
        individualFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = true;
        });
        organizationFields.querySelectorAll('input, textarea, select').forEach(field => {
            field.disabled = true;
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();

    // Debug form submission
    const form = document.querySelector('form[action*="verification"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const recipientType = document.getElementById('recipientType')?.value;
            console.log('Form submitting...', {
                recipientType: recipientType,
                hasGovernmentId: document.querySelector('input[name="government_id"]')?.files?.length > 0,
                hasProofOfAddress: document.querySelector('input[name="proof_of_address"]')?.files?.length > 0,
                hasRegistrationDocs: document.querySelector('input[name="registration_documents"]')?.files?.length > 0,
                organizationName: document.querySelector('input[name="organization_name"]')?.value
            });

            if (!recipientType) {
                e.preventDefault();
                alert('Please select recipient type (Individual or Organization)');
                return false;
            }
        });
    }
});
</script>
@endsection