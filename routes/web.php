<?php

// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkashPaymentController;
use App\Http\Controllers\NagadPaymentController;
use App\Http\Controllers\CampaignQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationHistoryController;
use App\Http\Controllers\CampaignCommentController;
use App\Http\Controllers\RecipientVerificationController;
use App\Http\Controllers\Admin\CampaignApprovalController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerSignupController;
use App\Http\Controllers\VolunteerDashboardController;
use App\Http\Controllers\FraudReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SavedCampaignController;



Route::get('/', function () {
    $campaigns = \App\Models\Campaign::active()->latest()->take(3)->get();
    $categories = \App\Models\Category::all();
    $stats = [
        'total_raised' => \App\Models\Donation::completed()->sum('amount'),
        'active_campaigns' => \App\Models\Campaign::active()->count(),
        'total_donors' => \App\Models\User::donors()->count(),
    ];
    return view('welcome', compact('campaigns', 'categories', 'stats'));
})->name('home');

// Temporary debug route - remove after testing
Route::get('/debug-user', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'logged_in' => true,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'verification_status' => $user->verification_status,
            'isRecipient' => $user->isRecipient(),
            'isVerified' => $user->isVerified(),
            'can_create_campaign' => $user->isRecipient() && $user->isVerified(),
        ]);
    } else {
        return response()->json(['logged_in' => false]);
    }
})->middleware('auth');

// Campaigns
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create')->middleware('auth');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/campaigns/{campaign}/qr-code', [CampaignController::class, 'downloadQr'])->name('campaigns.qr-code');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Redirect /reset-password to forgot password page
    Route::get('/reset-password', function() {
        return redirect()->route('password.request');
    });

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Campaigns (authenticated routes)
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::post('/campaigns/{campaign}/follow', [CampaignController::class, 'follow'])->name('campaigns.follow');

    // Donations
    Route::get('/campaigns/{campaign}/donate', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}/receipt', [DonationController::class, 'receipt'])->name('donations.receipt');

    // bKash Payment Routes
    Route::get('/campaigns/{campaign}/bkash-payment', [BkashPaymentController::class, 'show'])->name('bkash.payment');
    Route::post('/campaigns/{campaign}/bkash-payment', [BkashPaymentController::class, 'process'])->name('bkash.process');
    Route::get('/bkash-payment/success/{donation}', [BkashPaymentController::class, 'success'])->name('bkash.success');
    Route::get('/campaigns/{campaign}/bkash-payment/failed', [BkashPaymentController::class, 'failed'])->name('bkash.failed');

    // Nagad Payment Routes
    Route::get('/campaigns/{campaign}/nagad-payment', [NagadPaymentController::class, 'show'])->name('nagad.payment');
    Route::post('/campaigns/{campaign}/nagad-payment', [NagadPaymentController::class, 'process'])->name('nagad.process');
    Route::get('/nagad-payment/success/{donation}', [NagadPaymentController::class, 'success'])->name('nagad.success');
    Route::get('/campaigns/{campaign}/nagad-payment/failed', [NagadPaymentController::class, 'failed'])->name('nagad.failed');

    // Donation History PDF Downloads
    Route::get('/donation-history/pdf', [DonationHistoryController::class, 'downloadDonorHistory'])->name('donation-history.pdf');
    Route::get('/campaigns/{campaign}/donation-history/pdf', [DonationHistoryController::class, 'downloadCampaignHistory'])->name('campaign-donation-history.pdf');

    // Comments
    Route::post('/campaigns/{campaign}/comments', [CampaignCommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CampaignCommentController::class, 'destroy'])->name('comments.destroy');

    // Campaign Questions (Q&A)
    Route::post('/campaigns/{campaign}/questions', [CampaignQuestionController::class, 'store'])->name('questions.store');
    Route::post('/campaigns/{campaign}/questions/{question}/answer', [CampaignQuestionController::class, 'answer'])->name('questions.answer');
    Route::post('/campaigns/{campaign}/questions/{question}/toggle-pin', [CampaignQuestionController::class, 'togglePin'])->name('questions.toggle-pin');
    Route::delete('/campaigns/{campaign}/questions/{question}', [CampaignQuestionController::class, 'destroy'])->name('questions.destroy');

    // Recipient Verification
    Route::get('/verification', [RecipientVerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [RecipientVerificationController::class, 'store'])->name('verification.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Campaign Approval
    Route::get('/campaigns/pending', [CampaignApprovalController::class, 'index'])->name('campaigns.pending');
    Route::post('/campaigns/{campaign}/approve', [CampaignApprovalController::class, 'approve'])->name('campaigns.approve');
    Route::post('/campaigns/{campaign}/reject', [CampaignApprovalController::class, 'reject'])->name('campaigns.reject');
    Route::post('/campaigns/{campaign}/verify', [CampaignApprovalController::class, 'verify'])->name('campaigns.verify');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/change-role', [UserManagementController::class, 'changeRole'])->name('users.change-role');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/donation-history/pdf', [DonationHistoryController::class, 'downloadDonorHistory'])->name('users.donation-history.pdf');

    // Verification Management
    Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{verification}', [VerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{verification}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{verification}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/email-templates', [SettingsController::class, 'emailTemplates'])->name('settings.email-templates');
    Route::put('/settings/email-templates/{template}', [SettingsController::class, 'updateEmailTemplate'])->name('settings.email-templates.update');
    Route::get('/settings/static-pages', [SettingsController::class, 'staticPages'])->name('settings.static-pages');
    Route::put('/settings/static-pages/{page}', [SettingsController::class, 'updateStaticPage'])->name('settings.static-pages.update');
});

/*
|--------------------------------------------------------------------------
| Middleware Definition (Add to app/Http/Kernel.php)
|--------------------------------------------------------------------------
| 
| protected $middlewareAliases = [
|     'admin' => \App\Http\Middleware\AdminMiddleware::class,
| ];
*/


// Volunteer Signup Route

Route::post('/volunteer/signup', [VolunteerSignupController::class, 'store'])
    ->middleware('auth')
    ->name('volunteer.signup');


// Volunteer Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/volunteer/dashboard', [VolunteerDashboardController::class, 'index'])
        ->name('volunteer.dashboard');
});


// Fraud Report Route
Route::post('/fraud-report', [FraudReportController::class, 'store'])
    ->name('fraud.report')
    ->middleware('auth');



    // Receipt Route
Route::middleware('auth')->group(function () {

    Route::post('/campaigns/{campaign}/receipt',
        [ReceiptController::class, 'store']
    )->name('receipts.store');

    Route::get('/receipts',
        [ReceiptController::class, 'index']
    )->name('receipts.index');

    Route::get('/receipts/{receipt}',
        [ReceiptController::class, 'show']
    )->name('receipts.show');
});


// Saved Campaign Route

Route::middleware('auth')->group(function () {

    Route::get('/saved-campaigns', 
        [SavedCampaignController::class, 'index']
    )->name('saved-campaigns.index');

    Route::post('/campaigns/{campaign}/save', 
        [SavedCampaignController::class, 'store']
    )->name('campaigns.save');

    Route::delete('/campaigns/{campaign}/unsave', 
        [SavedCampaignController::class, 'destroy']
    )->name('campaigns.unsave');

});



