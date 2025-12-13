<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecipientVerification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        $pendingVerifications = RecipientVerification::pending()
            ->with('user')
            ->latest()
            ->get();

        return view('admin.verifications.index', compact('pendingVerifications'));
    }

    public function show(RecipientVerification $verification)
    {
        $verification->load('user');

        return view('admin.verifications.show', compact('verification'));
    }

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