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
        // Debug: Log that we reached the controller
        \Log::info('Verification store method called', [
            'user_id' => auth()->id(),
            'has_files' => $request->hasFile('government_id'),
            'recipient_type' => $request->input('recipient_type'),
            'all_data' => $request->except(['government_id', 'proof_of_address', 'registration_documents', 'tax_exempt_status', 'primary_contact_id'])
        ]);

        try {
            // Check if user already has a pending or approved verification
            $existingVerification = auth()->user()->verification;
            if ($existingVerification && $existingVerification->status === 'pending') {
                return redirect()->route('verification.index')
                    ->with('error', 'You already have a pending verification request.');
            }

            if ($existingVerification && $existingVerification->status === 'approved') {
                return redirect()->route('verification.index')
                    ->with('error', 'Your account is already verified.');
            }

            // Build validation rules based on recipient type
            $rules = [
                'recipient_type' => 'required|in:individual,organization',
            ];

            if ($request->recipient_type === 'individual') {
                $rules['government_id'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
                $rules['proof_of_address'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            } elseif ($request->recipient_type === 'organization') {
                $rules['organization_name'] = 'required|string|max:255';
                $rules['registration_documents'] = 'required|file|mimes:pdf|max:2048';
                $rules['tax_exempt_status'] = 'nullable|file|mimes:pdf|max:2048';
                $rules['primary_contact_name'] = 'required|string|max:255';
                $rules['primary_contact_id'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            }

            $validated = $request->validate($rules);

            $verification = new RecipientVerification();
            $verification->user_id = auth()->id();
            $verification->recipient_type = $request->recipient_type;

            if ($request->recipient_type === 'individual') {
                if ($request->hasFile('government_id')) {
                    $verification->government_id_path = $request->file('government_id')->store('verification', 'public');
                }
                if ($request->hasFile('proof_of_address')) {
                    $verification->proof_of_address_path = $request->file('proof_of_address')->store('verification', 'public');
                }
            } else {
                $verification->organization_name = $request->organization_name;
                if ($request->hasFile('registration_documents')) {
                    $verification->registration_documents_path = $request->file('registration_documents')->store('verification', 'public');
                }
                if ($request->hasFile('tax_exempt_status')) {
                    $verification->tax_exempt_status_path = $request->file('tax_exempt_status')->store('verification', 'public');
                }
                $verification->primary_contact_name = $request->primary_contact_name;
                if ($request->hasFile('primary_contact_id')) {
                    $verification->primary_contact_id_path = $request->file('primary_contact_id')->store('verification', 'public');
                }
            }

            $verification->status = 'pending';
            $verification->save();

            auth()->user()->update(['verification_status' => 'pending']);

            return redirect()->route('verification.index')
                ->with('success', 'Verification documents submitted successfully! We will review your submission within 24-48 hours.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Verification submission error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your verification. Please try again.')
                ->withInput();
        }
    }
}