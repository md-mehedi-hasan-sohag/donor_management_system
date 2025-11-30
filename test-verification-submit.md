# Testing Verification Submission

## Steps to Debug the Issue

### Step 1: Check Browser Console
1. Open the verification page: `/verification`
2. Press F12 to open Developer Tools
3. Go to the **Console** tab
4. Fill out the form and click Submit
5. Check for:
   - Console log message "Form submitting..." with form data
   - Any JavaScript errors
   - Any alert messages

### Step 2: Check Network Request
1. In Developer Tools, go to the **Network** tab
2. Fill out and submit the form
3. Look for a POST request to `/verification`
4. Click on it and check:
   - **Headers tab**: Should show `POST` method
   - **Payload tab**: Should show form data and files
   - **Response tab**: Should show redirect or error
   - **Status code**:
     - 302 = Redirect (success)
     - 422 = Validation error
     - 419 = CSRF token error
     - 500 = Server error

### Step 3: Check Laravel Logs
After submitting the form:

```bash
cd "g:\Programming\CSE470\donor_management_system"

# View latest log entries
tail -n 50 storage/logs/laravel.log

# Or open the full log file
notepad storage/logs/laravel.log
```

Look for:
- "Verification store method called" - confirms controller was reached
- Validation errors
- Exception messages

### Step 4: Manual Database Test
Test if data can be manually inserted:

```bash
php artisan tinker
```

Then run:
```php
$user = App\Models\User::where('role', 'recipient')->first();

if ($user) {
    $v = new App\Models\RecipientVerification();
    $v->user_id = $user->id;
    $v->recipient_type = 'individual';
    $v->status = 'pending';
    $v->save();

    echo "Success! Verification ID: " . $v->id . "\n";
} else {
    echo "No recipient user found\n";
}
```

If this works, the database is fine. If not, there's a model/database issue.

### Step 5: Verify User Role
Make sure you're logged in as a recipient:

```bash
php artisan tinker
```

```php
$user = App\Models\User::find(auth()->id());
echo "User ID: " . $user->id . "\n";
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Role: " . $user->role . "\n";
echo "Verification Status: " . $user->verification_status . "\n";
```

### Step 6: Test with Simple Data
Create a test with minimal data:

1. Select "Individual"
2. Upload a very small file (< 100KB) for Government ID
3. Upload a very small file (< 100KB) for Proof of Address
4. Check the certification checkbox
5. Submit

### Common Issues and Solutions

#### Issue 1: CSRF Token Mismatch (419 Error)
**Solution:** Clear browser cache and cookies, then try again

```bash
php artisan config:clear
php artisan cache:clear
```

#### Issue 2: File Upload Limit
**Check php.ini settings:**
- `upload_max_filesize` should be at least 2M
- `post_max_size` should be at least 8M
- `max_file_uploads` should be at least 5

**Solution:** Update php.ini or use smaller test files

#### Issue 3: Route Not Found
**Check if route exists:**
```bash
php artisan route:list --path=verification
```

Should show:
- GET/HEAD verification → verification.index
- POST verification → verification.store

#### Issue 4: Middleware Blocking Request
**Check if user is authenticated:**
- Must be logged in
- Must have `role = 'recipient'`

#### Issue 5: Form Not Submitting
**Check browser console for:**
- JavaScript errors preventing submission
- Form validation errors
- AJAX interference

### Quick Diagnostic Commands

```bash
# Check if verification table exists
php artisan db:table recipient_verifications

# Count existing verifications
php artisan tinker --execute="echo App\Models\RecipientVerification::count();"

# Check latest verification
php artisan tinker --execute="App\Models\RecipientVerification::latest()->first();"

# Clear all caches
php artisan optimize:clear
```

### What Should Happen

**Successful Submission Flow:**
1. User fills form → 2. Clicks submit → 3. JavaScript validates →
4. Form POSTs to /verification → 5. Controller receives data →
6. Validation passes → 7. Files uploaded → 8. Record saved to database →
9. Redirect to /verification → 10. Success message displayed

**Where is it failing?**
Use the steps above to pinpoint exactly where the process breaks.

### Expected Log Output

If everything works, you should see in `storage/logs/laravel.log`:

```
[2025-11-30 XX:XX:XX] local.INFO: Verification store method called {"user_id":X,"has_files":true,"recipient_type":"individual",...}
```

Followed by no errors, then a successful save.

### Still Not Working?

If you've tried all the above and it still doesn't work, provide:
1. Browser console output (screenshot)
2. Network tab POST request details (screenshot)
3. Last 50 lines of laravel.log
4. Output of: `php artisan route:list --path=verification`
5. User role from database

This will help identify the exact issue.
