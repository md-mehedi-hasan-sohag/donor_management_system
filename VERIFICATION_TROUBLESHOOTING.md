# Verification Submission Troubleshooting Guide

## Issue: Data not being saved from recipient verification page

### Quick Checklist

#### 1. **Check if you're logged in as a recipient**
   - Only users with `role = 'recipient'` can submit verifications
   - Check your user role in the database or user profile

#### 2. **Verify storage is configured**
   ```bash
   # Check if storage link exists
   ls -la public/storage

   # If not, create it
   php artisan storage:link

   # Verify verification directory exists
   ls -la storage/app/public/verification/
   ```

#### 3. **Check form submission**
   - Open browser Developer Tools (F12)
   - Go to Network tab
   - Submit the verification form
   - Look for the POST request to `/verification`
   - Check the response status code:
     - **302 Redirect**: Normal (check where it redirects)
     - **422 Validation Error**: Check validation errors in response
     - **500 Server Error**: Check Laravel logs

#### 4. **Check Laravel logs**
   ```bash
   # View recent errors
   tail -n 50 storage/logs/laravel.log
   ```

#### 5. **Common Issues and Solutions**

##### Issue: "The government id must be a file"
**Solution:** Make sure the form has `enctype="multipart/form-data"`
```html
<form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
```

##### Issue: "Validation fails silently"
**Solution:** Check if files are too large (max 2MB) or wrong format

##### Issue: "No error but data not saved"
**Solutions:**
1. Check database migration was run:
   ```bash
   php artisan migrate:status
   ```

2. Check if `recipient_verifications` table exists:
   ```bash
   php artisan db:show
   ```

3. Check User model has verification relationship:
   ```php
   public function verification() {
       return $this->hasOne(RecipientVerification::class);
   }
   ```

##### Issue: "Already have pending verification"
**Solution:** The system prevents duplicate submissions. Check existing verification:
```bash
php artisan tinker
>>> App\Models\RecipientVerification::where('user_id', YOUR_USER_ID)->get();
```

### Step-by-Step Testing

#### Test 1: Check if form is accessible
1. Login as recipient
2. Go to `/verification`
3. You should see the verification form
4. If you see "Account Verified" or "Verification Pending", you already have a verification

#### Test 2: Submit individual verification
1. Select "Individual" from dropdown
2. Upload a small test PDF or image for Government ID
3. Upload a small test PDF or image for Proof of Address
4. Check the certification checkbox
5. Click "Submit for Verification"
6. You should be redirected with success message

#### Test 3: Check if data was saved
```bash
php artisan tinker
>>> App\Models\RecipientVerification::latest()->first();
```

Should show your newly created verification record.

#### Test 4: Check admin can see it
1. Login as admin
2. Go to `/dashboard`
3. Should see "Pending Verifications" count increased
4. Should see new verification in "Pending Recipient Verifications" table
5. Or go directly to `/admin/verifications`

### Debugging Commands

#### Check verification count
```bash
php artisan tinker
>>> echo "Total verifications: " . App\Models\RecipientVerification::count() . "\n";
>>> echo "Pending: " . App\Models\RecipientVerification::pending()->count() . "\n";
>>> echo "Approved: " . App\Models\RecipientVerification::approved()->count() . "\n";
```

#### View all verifications
```bash
php artisan tinker
>>> App\Models\RecipientVerification::with('user')->get()->each(function($v) {
    echo "ID: {$v->id}, User: {$v->user->name}, Type: {$v->recipient_type}, Status: {$v->status}\n";
});
```

#### Check user's verification status
```bash
php artisan tinker
>>> $user = App\Models\User::find(YOUR_USER_ID);
>>> $user->verification;
>>> $user->verification_status;
```

#### Delete test verification (if needed)
```bash
php artisan tinker
>>> App\Models\RecipientVerification::where('user_id', YOUR_USER_ID)->delete();
```

### Updated Controller Features

The updated `RecipientVerificationController` now includes:

1. **Duplicate Prevention**: Won't allow submission if user already has pending/approved verification
2. **Better Error Handling**: Catches validation and general errors
3. **Error Logging**: Logs errors to `storage/logs/laravel.log`
4. **Better Redirects**: Returns to verification page with success/error messages
5. **Input Preservation**: Keeps form data on validation errors

### What to Check If Still Not Working

1. **Database Connection**
   ```bash
   php artisan db:show
   ```

2. **File Upload Limits**
   - Check `php.ini`:
     - `upload_max_filesize = 2M` (or higher)
     - `post_max_size = 8M` (or higher)
   - Check `.env`:
     - No conflicting settings

3. **Permissions**
   ```bash
   # On Linux/Mac
   chmod -R 775 storage/app/public/verification

   # Check ownership
   ls -la storage/app/public/verification
   ```

4. **Browser Console Errors**
   - Open F12 Developer Tools
   - Check Console tab for JavaScript errors
   - Check if form is submitting correctly

5. **Network Request**
   - Open F12 Developer Tools → Network tab
   - Submit form
   - Click on the `/verification` POST request
   - Check:
     - Request payload (should contain form data and files)
     - Response (should be 302 redirect or validation errors)
     - Response headers (check redirect location)

### Example Working Flow

1. **Recipient submits form** →
2. **POST to `/verification`** →
3. **Validation passes** →
4. **Files uploaded to `storage/app/public/verification/`** →
5. **Record saved to `recipient_verifications` table** →
6. **User's `verification_status` updated to 'pending'** →
7. **Redirect to `/verification` with success message** →
8. **Page shows "Verification Pending" status** →
9. **Admin sees in dashboard and `/admin/verifications`**

### Quick Test Script

Create a test file `test-verification.php` in project root:

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Verification System\n";
echo "===========================\n\n";

// Test database connection
try {
    $count = App\Models\RecipientVerification::count();
    echo "✓ Database connected\n";
    echo "  Total verifications: {$count}\n\n";
} catch (\Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test storage directory
$storagePath = storage_path('app/public/verification');
if (is_dir($storagePath) && is_writable($storagePath)) {
    echo "✓ Storage directory exists and is writable\n";
    echo "  Path: {$storagePath}\n\n";
} else {
    echo "✗ Storage directory issue\n";
    echo "  Path: {$storagePath}\n";
    echo "  Exists: " . (is_dir($storagePath) ? 'Yes' : 'No') . "\n";
    echo "  Writable: " . (is_writable($storagePath) ? 'Yes' : 'No') . "\n\n";
}

// Test symlink
$publicLink = public_path('storage');
if (is_link($publicLink) || is_dir($publicLink)) {
    echo "✓ Public storage link exists\n";
    echo "  Path: {$publicLink}\n\n";
} else {
    echo "✗ Public storage link missing\n";
    echo "  Run: php artisan storage:link\n\n";
}

echo "Test complete!\n";
```

Run it:
```bash
php test-verification.php
```

### Still Having Issues?

If data still isn't being saved:

1. **Enable query logging** in `AppServiceProvider`:
   ```php
   \DB::listen(function($query) {
       \Log::info($query->sql, $query->bindings);
   });
   ```

2. **Add dd() debugging** in controller:
   ```php
   public function store(Request $request)
   {
       dd($request->all()); // Check what's being received
       // ... rest of code
   }
   ```

3. **Check if validation is failing**:
   ```php
   try {
       $validated = $request->validate([...]);
   } catch (\Illuminate\Validation\ValidationException $e) {
       dd($e->errors()); // See validation errors
   }
   ```

4. **Verify model is saveable**:
   ```bash
   php artisan tinker
   >>> $v = new App\Models\RecipientVerification();
   >>> $v->user_id = 1;
   >>> $v->recipient_type = 'individual';
   >>> $v->status = 'pending';
   >>> $v->save();
   ```

If this fails, there's a database or model configuration issue.
