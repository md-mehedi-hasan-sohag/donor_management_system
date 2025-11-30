# Recipient Verification - Quick Code Reference

## Essential Code Snippets for Recipient Verification

---

## 1. Model Code

### RecipientVerification Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipientVerification extends Model
{
    protected $fillable = [
        'user_id', 'recipient_type', 'government_id_path',
        'proof_of_address_path', 'organization_name',
        'registration_documents_path', 'tax_exempt_status_path',
        'primary_contact_name', 'primary_contact_id_path',
        'status', 'rejection_reason', 'reviewed_by', 'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query) {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query) {
        return $query->where('status', 'rejected');
    }
}
```

---

## 2. Controller Code

### Recipient Submission Controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\RecipientVerification;
use Illuminate\Http\Request;

class RecipientVerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $verification = $user->verification;
        return view('recipient.verification', compact('verification'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:individual,organization',
            'government_id' => 'required_if:recipient_type,individual|file|mimes:pdf,jpg,png|max:2048',
            'proof_of_address' => 'required_if:recipient_type,individual|file|mimes:pdf,jpg,png|max:2048',
            'organization_name' => 'required_if:recipient_type,organization|string|max:255',
            'registration_documents' => 'required_if:recipient_type,organization|file|mimes:pdf|max:2048',
            'tax_exempt_status' => 'nullable|file|mimes:pdf|max:2048',
            'primary_contact_name' => 'required_if:recipient_type,organization|string|max:255',
            'primary_contact_id' => 'required_if:recipient_type,organization|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $verification = new RecipientVerification();
        $verification->user_id = auth()->id();
        $verification->recipient_type = $request->recipient_type;

        // Handle individual documents
        if ($request->recipient_type === 'individual') {
            if ($request->hasFile('government_id')) {
                $verification->government_id_path = $request->file('government_id')
                    ->store('verification', 'public');
            }
            if ($request->hasFile('proof_of_address')) {
                $verification->proof_of_address_path = $request->file('proof_of_address')
                    ->store('verification', 'public');
            }
        }

        // Handle organization documents
        else {
            $verification->organization_name = $request->organization_name;
            if ($request->hasFile('registration_documents')) {
                $verification->registration_documents_path = $request->file('registration_documents')
                    ->store('verification', 'public');
            }
            if ($request->hasFile('tax_exempt_status')) {
                $verification->tax_exempt_status_path = $request->file('tax_exempt_status')
                    ->store('verification', 'public');
            }
            $verification->primary_contact_name = $request->primary_contact_name;
            if ($request->hasFile('primary_contact_id')) {
                $verification->primary_contact_id_path = $request->file('primary_contact_id')
                    ->store('verification', 'public');
            }
        }

        $verification->status = 'pending';
        $verification->save();

        auth()->user()->update(['verification_status' => 'pending']);

        return redirect()->route('recipient.dashboard')
            ->with('success', 'Verification documents submitted successfully!');
    }
}
```

### Admin Review Controller
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecipientVerification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // List all pending verifications
    public function index()
    {
        $pendingVerifications = RecipientVerification::pending()
            ->with('user')
            ->latest()
            ->get();

        return view('admin.verifications.index', compact('pendingVerifications'));
    }

    // Show specific verification details
    public function show(RecipientVerification $verification)
    {
        $verification->load('user');
        return view('admin.verifications.show', compact('verification'));
    }

    // Approve verification
    public function approve(RecipientVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $verification->user->update([
            'verification_status' => 'verified',
        ]);

        return back()->with('success', 'Verification approved!');
    }

    // Reject verification
    public function reject(Request $request, RecipientVerification $verification)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Verification rejected.');
    }
}
```

---

## 3. Route Definitions

```php
use App\Http\Controllers\RecipientVerificationController;
use App\Http\Controllers\Admin\VerificationController;

// Recipient routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/verification', [RecipientVerificationController::class, 'index'])
        ->name('verification.index');
    Route::post('/verification', [RecipientVerificationController::class, 'store'])
        ->name('verification.store');
});

// Admin routes (authenticated admins)
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

## 4. Database Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipient_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('recipient_type', ['individual', 'organization']);

            // Individual fields
            $table->string('government_id_path')->nullable();
            $table->string('proof_of_address_path')->nullable();

            // Organization fields
            $table->string('organization_name')->nullable();
            $table->string('registration_documents_path')->nullable();
            $table->string('tax_exempt_status_path')->nullable();
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_id_path')->nullable();

            // Review fields
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipient_verifications');
    }
};
```

---

## 5. Common Blade Snippets

### Check Verification Status
```blade
@if(auth()->user()->verification && auth()->user()->verification->status === 'approved')
    <!-- User is verified -->
    <span class="badge badge-success">Verified</span>
@elseif(auth()->user()->verification && auth()->user()->verification->status === 'pending')
    <!-- Verification pending -->
    <span class="badge badge-warning">Verification Pending</span>
@else
    <!-- Not verified -->
    <a href="{{ route('verification.index') }}" class="btn btn-primary">
        Get Verified
    </a>
@endif
```

### Admin Route Names (with admin. prefix)
```blade
<!-- List verifications -->
<a href="{{ route('admin.verifications.index') }}">Verifications</a>

<!-- View specific verification -->
<a href="{{ route('admin.verifications.show', $verification) }}">Review</a>

<!-- Approve form -->
<form action="{{ route('admin.verifications.approve', $verification) }}" method="POST">
    @csrf
    <button type="submit">Approve</button>
</form>

<!-- Reject form -->
<form action="{{ route('admin.verifications.reject', $verification) }}" method="POST">
    @csrf
    <textarea name="rejection_reason" required></textarea>
    <button type="submit">Reject</button>
</form>
```

### Display Document Link
```blade
@if($verification->government_id_path)
    <a href="{{ asset('storage/' . $verification->government_id_path) }}"
       target="_blank"
       class="btn btn-secondary">
        View Document
    </a>
@endif
```

### Form with File Upload
```blade
<form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <select name="recipient_type" required>
        <option value="individual">Individual</option>
        <option value="organization">Organization</option>
    </select>

    <input type="file" name="government_id" accept=".pdf,.jpg,.png">

    <button type="submit">Submit for Verification</button>
</form>
```

---

## 6. Useful Helper Functions

### Check User Verification in Controller
```php
// Check if user is verified
if (auth()->user()->isVerified()) {
    // User is verified
}

// Get user's verification
$verification = auth()->user()->verification;

// Check specific status
if ($verification && $verification->status === 'approved') {
    // Verification approved
}
```

### Query Examples
```php
// Get all pending verifications
$pending = RecipientVerification::pending()->get();

// Get verifications with user info
$verifications = RecipientVerification::with('user', 'reviewer')->get();

// Count pending verifications
$count = RecipientVerification::pending()->count();

// Get recent approvals
$recent = RecipientVerification::approved()
    ->latest('reviewed_at')
    ->limit(10)
    ->get();
```

---

## 7. Middleware Protection

### Protect Routes Based on Verification
```php
// In your controller or middleware
public function handle($request, Closure $next)
{
    if (!auth()->user()->isVerified()) {
        return redirect()->route('verification.index')
            ->with('error', 'Please complete verification first.');
    }

    return $next($request);
}
```

### Campaign Creation Check
```php
// In CampaignController
public function create()
{
    if (!auth()->user()->isVerified()) {
        return redirect()->route('verification.index')
            ->with('error', 'You must be verified to create campaigns.');
    }

    return view('campaigns.create');
}
```

---

## 8. Validation Rules Quick Reference

```php
$rules = [
    // Common
    'recipient_type' => 'required|in:individual,organization',

    // Individual
    'government_id' => 'required_if:recipient_type,individual|file|mimes:pdf,jpg,png|max:2048',
    'proof_of_address' => 'required_if:recipient_type,individual|file|mimes:pdf,jpg,png|max:2048',

    // Organization
    'organization_name' => 'required_if:recipient_type,organization|string|max:255',
    'registration_documents' => 'required_if:recipient_type,organization|file|mimes:pdf|max:2048',
    'tax_exempt_status' => 'nullable|file|mimes:pdf|max:2048',
    'primary_contact_name' => 'required_if:recipient_type,organization|string|max:255',
    'primary_contact_id' => 'required_if:recipient_type,organization|file|mimes:pdf,jpg,png|max:2048',

    // Admin
    'rejection_reason' => 'required|string|max:500',
];
```

---

## 9. File Storage

### Store Files
```php
// Store in public/storage/verification/
$path = $request->file('government_id')->store('verification', 'public');

// Save path to database
$verification->government_id_path = $path;
```

### Access Files
```php
// In Blade view
<a href="{{ asset('storage/' . $verification->government_id_path) }}">View</a>

// In controller
$fullPath = storage_path('app/public/' . $verification->government_id_path);
```

### Delete Files
```php
use Illuminate\Support\Facades\Storage;

// Delete a file
Storage::disk('public')->delete($verification->government_id_path);
```

---

## 10. Setup Commands

```bash
# Run migration
php artisan migrate

# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage/app/public

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Quick Navigation

| File | Location |
|------|----------|
| Model | `app/Models/RecipientVerification.php` |
| Recipient Controller | `app/Http/Controllers/RecipientVerificationController.php` |
| Admin Controller | `app/Http/Controllers/Admin/VerificationController.php` |
| Recipient View | `resources/views/recipient/verification.blade.php` |
| Admin List View | `resources/views/admin/verifications/index.blade.php` |
| Admin Detail View | `resources/views/admin/verifications/show.blade.php` |
| Migration | `database/migrations/2025_11_17_183653_create_recipient_verifications_table.php` |
| Routes | `routes/web.php` (lines 79-80, 105-108) |

---

## Status Flow

```
NOT VERIFIED → PENDING → APPROVED ✅
                   ↓
                REJECTED ❌ → (can resubmit) → PENDING
```

---

## Common Tasks

### 1. Create Verification Request
```php
$verification = RecipientVerification::create([
    'user_id' => auth()->id(),
    'recipient_type' => 'individual',
    'government_id_path' => 'verification/abc123.pdf',
    'status' => 'pending',
]);
```

### 2. Approve Verification
```php
$verification->update([
    'status' => 'approved',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
]);
$verification->user->update(['verification_status' => 'verified']);
```

### 3. Reject Verification
```php
$verification->update([
    'status' => 'rejected',
    'rejection_reason' => 'Documents unclear',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
]);
```

### 4. Check Pending Count
```php
$pendingCount = RecipientVerification::pending()->count();
```

---

## Security Notes

1. Always validate file uploads
2. Use `required_if` for conditional validation
3. Store files in `storage/app/public`, not `public/`
4. Protect admin routes with middleware
5. Sanitize user input
6. Check file MIME types
7. Limit file sizes (max 2MB)

---

This reference covers all essential code for the recipient verification system!
