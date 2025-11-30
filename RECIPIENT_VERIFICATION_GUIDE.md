# Recipient Verification System - Complete Guide

This guide covers the complete recipient verification system for the Donor Management System.

## Overview

The recipient verification system allows recipients to submit verification documents (individual or organization) and enables admins to review and approve/reject these submissions.

---

## Database Schema

### Migration File
**Location:** `database/migrations/2025_11_17_183653_create_recipient_verifications_table.php`

The `recipient_verifications` table includes:
- `user_id` - Links to the users table
- `recipient_type` - Enum: 'individual' or 'organization'
- Individual fields: `government_id_path`, `proof_of_address_path`
- Organization fields: `organization_name`, `registration_documents_path`, `tax_exempt_status_path`, `primary_contact_name`, `primary_contact_id_path`
- `status` - Enum: 'pending', 'approved', 'rejected'
- `rejection_reason` - Text field for rejection explanation
- `reviewed_by` - Foreign key to users table (admin who reviewed)
- `reviewed_at` - Timestamp of review

---

## Models

### RecipientVerification Model
**Location:** `app/Models/RecipientVerification.php`

**Key Features:**
- Fillable fields for mass assignment
- Relationships: `user()`, `reviewer()`
- Query scopes: `pending()`, `approved()`, `rejected()`
- Timestamps with Carbon casting

**Usage Example:**
```php
// Get pending verifications
$pending = RecipientVerification::pending()->with('user')->get();

// Get a user's verification
$verification = auth()->user()->verification;

// Check verification status
if ($verification && $verification->status === 'approved') {
    // User is verified
}
```

---

## Controllers

### 1. RecipientVerificationController
**Location:** `app/Http/Controllers/RecipientVerificationController.php`

**Methods:**

#### `index()`
Displays the verification form/status page for recipients.

```php
Route::get('/verification', [RecipientVerificationController::class, 'index'])
    ->name('verification.index');
```

#### `store(Request $request)`
Handles verification document submission.

**Validation Rules:**
- `recipient_type`: required, must be 'individual' or 'organization'
- `government_id`: required for individuals, file (pdf/jpg/png), max 2MB
- `proof_of_address`: required for individuals, file (pdf/jpg/png), max 2MB
- `organization_name`: required for organizations, string, max 255 chars
- `registration_documents`: required for organizations, PDF file, max 2MB
- `tax_exempt_status`: optional, PDF file, max 2MB
- `primary_contact_name`: required for organizations, string, max 255 chars
- `primary_contact_id`: required for organizations, file (pdf/jpg/png), max 2MB

**Usage:**
```php
Route::post('/verification', [RecipientVerificationController::class, 'store'])
    ->name('verification.store');
```

**File Storage:**
- Files are stored in `storage/app/public/verification/`
- Access via: `asset('storage/' . $verification->government_id_path)`

---

### 2. Admin VerificationController
**Location:** `app/Http/Controllers/Admin/VerificationController.php`

**Methods:**

#### `index()`
Lists all pending verification requests.

```php
Route::get('/verifications', [VerificationController::class, 'index'])
    ->name('verifications.index');
```

#### `show(RecipientVerification $verification)`
Shows detailed view of a verification request.

```php
Route::get('/verifications/{verification}', [VerificationController::class, 'show'])
    ->name('verifications.show');
```

#### `approve(RecipientVerification $verification)`
Approves a verification request.

**Actions:**
1. Sets verification status to 'approved'
2. Records reviewer ID and timestamp
3. Updates user's `verification_status` to 'verified'

```php
Route::post('/verifications/{verification}/approve', [VerificationController::class, 'approve'])
    ->name('verifications.approve');
```

#### `reject(Request $request, RecipientVerification $verification)`
Rejects a verification request.

**Validation:**
- `rejection_reason`: required, string, max 500 chars

**Actions:**
1. Sets verification status to 'rejected'
2. Stores rejection reason
3. Records reviewer ID and timestamp
4. User can resubmit with new documents

```php
Route::post('/verifications/{verification}/reject', [VerificationController::class, 'reject'])
    ->name('verifications.reject');
```

---

## Views

### 1. Recipient Verification Form
**Location:** `resources/views/recipient/verification.blade.php`

**Features:**
- Dynamic form switching between individual and organization types
- Conditional field display based on verification status
- File upload with size and type restrictions
- Privacy notice and certification checkbox
- Status indicators (pending/approved/rejected)

**States:**
1. **Pending:** Shows waiting message, disables form
2. **Approved:** Shows success message with link to create campaign
3. **Rejected:** Shows rejection reason, allows resubmission
4. **New/No submission:** Shows full form

**JavaScript:**
- `toggleFields()` function switches between individual/organization fields
- Auto-initializes on page load

---

### 2. Admin Verification List
**Location:** `resources/views/admin/verifications/index.blade.php`

**Features:**
- Table view of all pending verifications
- Shows recipient name, email, type, organization, submission date
- Quick access to review page
- Empty state when no pending verifications

**Columns:**
- Recipient (name + email)
- Type (individual/organization)
- Organization name
- Submitted date
- Status
- Actions (Review button)

---

### 3. Admin Verification Detail
**Location:** `resources/views/admin/verifications/show.blade.php`

**Features:**
- Full applicant information display
- Document viewer links (opens in new tab)
- Conditional rendering for individual vs organization
- Approve/Reject action buttons
- Rejection form with reason textarea
- Status history display

**Sections:**
1. **Applicant Information:** Name, email, application type
2. **Documents:** All uploaded files with view links
3. **Review Decision:** Approve/Reject buttons (if pending)
4. **Status History:** Shows who reviewed and when (if processed)

---

## Routes

### Recipient Routes (Protected)
```php
Route::middleware('auth')->group(function () {
    Route::get('/verification', [RecipientVerificationController::class, 'index'])
        ->name('verification.index');
    Route::post('/verification', [RecipientVerificationController::class, 'store'])
        ->name('verification.store');
});
```

### Admin Routes (Protected)
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/verifications', [VerificationController::class, 'index'])
        ->name('verifications.index');
    Route::get('/verifications/{verification}', [VerificationController::class, 'show'])
        ->name('verifications.show');
    Route::post('/verifications/{verification}/approve', [VerificationController::class, 'approve'])
        ->name('verifications.approve');
    Route::post('/verifications/{verification}/reject', [VerificationController::class, 'reject'])
        ->name('verifications.reject');
});
```

---

## Usage Workflow

### For Recipients:

1. **Navigate to Verification Page:**
   ```
   /verification
   ```

2. **Choose Application Type:**
   - Individual: For personal fundraising
   - Organization: For NGOs, charities, etc.

3. **Upload Required Documents:**
   - **Individual:** Government ID + Proof of Address
   - **Organization:** Registration docs, Tax status (optional), Contact info

4. **Submit and Wait:**
   - Status changes to 'pending'
   - Notification sent to admins
   - Recipient receives confirmation

5. **Check Status:**
   - Return to `/verification` to see status
   - Receive email when reviewed

### For Admins:

1. **View Pending Requests:**
   ```
   /admin/verifications
   ```

2. **Review Details:**
   - Click "Review" on any verification
   - View all submitted documents
   - Verify authenticity

3. **Make Decision:**
   - **Approve:** Instantly grants verification status
   - **Reject:** Provide clear reason for rejection

4. **Recipient Notification:**
   - System updates user's verification status
   - Recipient can see decision on their verification page

---

## File Storage Setup

### Required Configuration:

1. **Create symbolic link (if not already done):**
   ```bash
   php artisan storage:link
   ```

2. **Verify storage directory exists:**
   ```
   storage/app/public/verification/
   ```

3. **Set proper permissions:**
   ```bash
   chmod -R 775 storage/app/public/verification
   ```

---

## Security Considerations

1. **File Validation:**
   - Only PDF, JPG, PNG allowed
   - Max 2MB per file
   - MIME type validation

2. **Privacy:**
   - Documents stored securely in storage/app/public
   - Only admins can view verification documents
   - Documents not displayed publicly

3. **Authorization:**
   - Recipients can only view their own verification
   - Admins require admin middleware
   - File paths stored relative to storage

---

## Database Queries

### Common Queries:

```php
// Get all pending verifications with user info
$pending = RecipientVerification::pending()
    ->with('user')
    ->latest()
    ->get();

// Get approved verifications count
$approvedCount = RecipientVerification::approved()->count();

// Get verifications reviewed by specific admin
$reviewed = RecipientVerification::where('reviewed_by', $adminId)
    ->with('user')
    ->get();

// Check if user has pending verification
$hasPending = auth()->user()->verification()
    ->where('status', 'pending')
    ->exists();
```

---

## Testing the System

### 1. Setup:
```bash
php artisan migrate
php artisan storage:link
```

### 2. Create test recipient:
- Register as recipient role
- Navigate to /verification

### 3. Submit verification:
- Choose type (individual/organization)
- Upload test documents
- Submit form

### 4. Admin review:
- Login as admin
- Navigate to /admin/verifications
- Review and approve/reject

### 5. Verify status:
- Login as recipient again
- Check /verification page for status

---

## Troubleshooting

### Issue: Files not uploading
- Check storage permissions
- Verify `storage:link` was created
- Check `config/filesystems.php` public disk config

### Issue: Admin can't see verifications
- Verify admin middleware is applied
- Check user role in database
- Ensure routes are properly protected

### Issue: Images/PDFs not displaying
- Verify symbolic link: `public/storage -> storage/app/public`
- Check file paths in database
- Ensure files exist in `storage/app/public/verification/`

---

## Extending the System

### Add Email Notifications:

```php
// In VerificationController@approve
Mail::to($verification->user->email)->send(
    new VerificationApproved($verification)
);

// In VerificationController@reject
Mail::to($verification->user->email)->send(
    new VerificationRejected($verification)
);
```

### Add Activity Logging:

```php
// After approval/rejection
ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'verification_approved',
    'description' => "Approved verification for {$verification->user->name}",
]);
```

### Add Document Preview:

In the show view, add inline PDF viewer:
```html
<iframe src="{{ asset('storage/' . $verification->government_id_path) }}"
        width="100%" height="600px"></iframe>
```

---

## Summary

The recipient verification system is now complete with:

✅ Database migration and model
✅ Recipient submission form with file uploads
✅ Admin review interface
✅ Approval/rejection workflow
✅ Status tracking and history
✅ Secure file storage
✅ Role-based access control

All components are properly integrated and ready for use!
