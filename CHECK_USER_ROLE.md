# CRITICAL: Check Your User Role!

## The Problem

The verification form is only accessible when logged in as a **RECIPIENT** user.

If you're logged in as **ADMIN** or **DONOR**, the form won't work properly!

## How to Check Your Current Role

Open your browser Developer Tools (F12) and go to the Console tab, then paste this:

```javascript
fetch('/api/user')
  .then(r => r.json())
  .then(data => console.log('Your role:', data.role))
  .catch(() => console.log('Check failed'));
```

Or check manually:
1. Look at the dashboard URL after login
2. If you see `/dashboard` and it shows **admin features**, you're logged in as admin
3. Recipient users should see recipient dashboard with campaign management options

## Solution: Login as Recipient User

You need to:
1. **Logout** from your current admin account
2. **Login** as a recipient user
3. Then go to `/verification` and submit the form

## How to Create a Recipient User

If you don't have a recipient user, create one:

### Option 1: Register as Recipient
1. Logout
2. Go to `/register`
3. Fill the form and select role as "Recipient"
4. Login with new account
5. Go to `/verification`

### Option 2: Change Existing User Role via Database

```bash
php artisan tinker
```

```php
// Find a user (change email to yours)
$user = App\Models\User::where('email', 'your-email@example.com')->first();

// Change role to recipient
$user->role = 'recipient';
$user->save();

echo "User {$user->name} is now a recipient\n";
exit;
```

## Why This Matters

The `/verification` route is protected by **auth middleware** which checks if the user is logged in.

But more importantly:
- **Recipients** submit verifications
- **Admins** approve/reject verifications

If you're logged in as admin and try to submit, the system might redirect you or behave unexpectedly.

## Test After Login as Recipient

1. Login as recipient user
2. Go to `/verification`
3. You should see the verification form
4. Fill it out and submit
5. After submission, you should see "Verification Pending" message
6. Then login as admin to approve it
