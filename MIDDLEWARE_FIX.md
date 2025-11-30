# Admin Middleware Fix

## Problem
```
Illuminate\Contracts\Container\BindingResolutionException
Target class [admin] does not exist.
```

This error occurred because the `admin` middleware alias was not registered in Laravel 11's new configuration system.

## Solution

### What Was Changed

**File:** `bootstrap/app.php`

**Before:**
```php
->withMiddleware(function (Middleware $middleware): void {
    //
})
```

**After:**
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

## Explanation

In Laravel 11, middleware aliases are no longer registered in `app/Http/Kernel.php`. Instead, they are registered in `bootstrap/app.php` using the `withMiddleware()` method.

The admin middleware class already existed at:
- `app/Http/Middleware/AdminMiddleware.php`

It just needed to be registered with the alias `'admin'` so that routes can reference it like:
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

## Cache Cleared

After the fix, the following caches were cleared:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## Result

✅ The admin middleware is now properly registered
✅ Admin routes work correctly
✅ Verification system is fully functional

## Testing

To verify the fix works:

1. Login as an admin user
2. Navigate to `/admin/verifications`
3. You should see the verifications list without errors

If you're not an admin, you should see a 403 Unauthorized error, which is correct behavior.
