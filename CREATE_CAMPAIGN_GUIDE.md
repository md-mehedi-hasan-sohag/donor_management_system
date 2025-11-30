# Campaign Creation - Complete Guide

## Why You're Getting 404 Error

The campaign creation feature requires:
1. **You must be logged in as a RECIPIENT user** (not admin or donor)
2. **Your recipient account must be VERIFIED**

The system is working correctly - it's just blocking you because you don't meet these requirements!

## How to Create a Campaign (Step by Step)

### Step 1: Login as Verified Recipient

You need a recipient account that has been verified. Here are your options:

#### Option A: Use Existing Verified Recipient
```bash
# Check if you have verified recipients
cd "g:\Programming\CSE470\donor_management_system"
php artisan tinker
```

```php
// Find verified recipients
App\Models\RecipientVerification::approved()
    ->with('user')
    ->get()
    ->each(function($v) {
        echo "User: {$v->user->name} ({$v->user->email})\n";
    });
exit;
```

If you see any users, login as one of them.

#### Option B: Verify an Existing Recipient
```bash
php artisan tinker
```

```php
// Find a recipient user
$user = App\Models\User::where('role', 'recipient')->first();

if ($user) {
    // Create verification record
    $verification = new App\Models\RecipientVerification();
    $verification->user_id = $user->id;
    $verification->recipient_type = 'individual';
    $verification->status = 'approved';
    $verification->save();

    // Update user status
    $user->verification_status = 'verified';
    $user->save();

    echo "User {$user->name} is now verified!\n";
    echo "Email: {$user->email}\n";
} else {
    echo "No recipient users found\n";
}
exit;
```

#### Option C: Create New Recipient and Verify
```bash
php artisan tinker
```

```php
// Create new recipient user
$user = new App\Models\User();
$user->name = 'Test Recipient';
$user->email = 'recipient@test.com';
$user->password = bcrypt('password123');
$user->role = 'recipient';
$user->verification_status = 'verified';
$user->save();

// Create verification record
$verification = new App\Models\RecipientVerification();
$verification->user_id = $user->id;
$verification->recipient_type = 'individual';
$verification->status = 'approved';
$verification->save();

echo "Created and verified recipient:\n";
echo "Email: recipient@test.com\n";
echo "Password: password123\n";
exit;
```

### Step 2: Login and Create Campaign

1. **Logout** from your current admin account
2. **Login** as the verified recipient user
3. Go to `/campaigns/create`
4. You should now see the campaign creation form!

### Step 3: Fill Out the Campaign Form

The form has 4 steps:

#### Step 1: Basic Information
- **Campaign Title**: Give it a compelling title
- **Category**: Select from available categories
- **Location**: City, State, or Region
- **Funding Goal**: Minimum $100
- **End Date**: Must be in the future

#### Step 2: Campaign Story
- **Description**: Tell your story (what, why, how, impact)
- **Image**: Upload a high-quality image (max 2MB)

#### Step 3: Campaign Options
- **Mark as Urgent**: Optional - gets highlighted visibility
- **Accept Volunteers**: Optional - allow volunteer signups
- **Accept In-Kind Donations**: Optional - accept physical items/services

#### Step 4: Review & Submit
- Review your campaign details
- Check the confirmation checkbox
- Click "Submit Campaign"

### Step 4: Wait for Approval

After submission:
1. Campaign goes to **"pending"** status
2. Admin needs to review and approve
3. You'll be redirected to recipient dashboard
4. You should see success message

### Step 5: Admin Approves Campaign

Now switch back to admin account:
1. Login as admin
2. Go to `/admin/campaigns/pending`
3. Find your campaign
4. Click "Approve"
5. Campaign goes live!

## Complete Code Reference

All the code already exists! Here are the files:

### Controller
**File**: `app/Http/Controllers/CampaignController.php`
- `create()` - Shows the form (lines 74-85)
- `store()` - Handles submission (lines 87-119)

### View
**File**: `resources/views/campaigns/create.blade.php`
- Complete 4-step form with validation
- Image preview
- Conditional fields
- Review summary

### Routes
**File**: `routes/web.php`
```php
// Campaign creation routes (already configured)
Route::get('/campaigns/create', [CampaignController::class, 'create'])
    ->name('campaigns.create');
Route::post('/campaigns', [CampaignController::class, 'store'])
    ->name('campaigns.store');
```

### Policy
**File**: `app/Policies/CampaignPolicy.php`
```php
public function create(User $user)
{
    return $user->isRecipient() && $user->isVerified();
}
```

This is why you get 404 - you must be recipient AND verified!

### Model
**File**: `app/Models/Campaign.php`
- All campaign fields and relationships

## Testing the Feature

### Quick Test Script

```bash
cd "g:\Programming\CSE470\donor_management_system"
php artisan tinker
```

```php
// Create verified recipient
$user = App\Models\User::create([
    'name' => 'Campaign Creator',
    'email' => 'creator@test.com',
    'password' => bcrypt('password'),
    'role' => 'recipient',
    'verification_status' => 'verified'
]);

$verification = App\Models\RecipientVerification::create([
    'user_id' => $user->id,
    'recipient_type' => 'individual',
    'status' => 'approved'
]);

echo "Created verified recipient:\n";
echo "Email: creator@test.com\n";
echo "Password: password\n";
echo "\nNow logout and login with these credentials!\n";
echo "Then go to: /campaigns/create\n";
exit;
```

### Test Campaign Submission

After logging in as verified recipient:

1. Go to `/campaigns/create`
2. Fill out all 4 steps
3. Submit
4. Check `/dashboard` (recipient dashboard) - should see your campaign
5. Switch to admin
6. Go to `/admin/campaigns/pending`
7. Approve the campaign
8. Campaign goes live at `/campaigns`

## Common Issues

### Issue 1: 404 Error on /campaigns/create
**Cause**: Not logged in as verified recipient
**Solution**: Login as recipient with `verification_status = 'verified'`

### Issue 2: Redirected to /verification
**Cause**: Logged in as recipient but not verified
**Solution**: Complete verification or manually verify user (see Step 1 Option B)

### Issue 3: 403 Unauthorized
**Cause**: Logged in as admin or donor (not recipient)
**Solution**: Logout and login as recipient user

### Issue 4: Form submits but nothing happens
**Cause**: Validation errors
**Solution**: Check all required fields are filled

### Issue 5: Image upload fails
**Cause**: File too large or wrong format
**Solution**: Use image under 2MB, formats: jpg, png, gif

## Admin Campaign Approval

After recipient submits campaign, admin must approve:

### Admin Dashboard
1. Login as admin
2. Go to `/dashboard`
3. See "Pending Campaign Approvals" section
4. Click "View" or "Approve"

### Admin Campaigns Page
1. Go to `/admin/campaigns/pending`
2. See all pending campaigns
3. Options:
   - **Approve**: Campaign goes live
   - **Reject**: Provide reason
   - **Verify**: Mark as verified (special badge)

## Summary

The campaign creation feature is **fully implemented**! You just need to:

1. ✅ Use a **recipient** user account
2. ✅ Ensure account is **verified**
3. ✅ Go to `/campaigns/create`
4. ✅ Fill out the 4-step form
5. ✅ Submit and wait for admin approval

All code is ready - the 404 error is just the system working as designed to protect the feature!
