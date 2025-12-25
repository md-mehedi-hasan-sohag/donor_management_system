<?php

namespace App\Http\Controllers;

use App\Models\VolunteerSignup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerSignupController extends Controller
{
    /**
     * Store a new volunteer signup.
     */
    public function store(Request $request)
    {
        // Ensure user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate request data
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',
            'message'     => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate signup for the same campaign
        $alreadyApplied = VolunteerSignup::where('campaign_id', $request->campaign_id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return redirect()->back()
                ->with('error', 'You have already applied as a volunteer for this campaign.');
        }

        // Create volunteer signup
        VolunteerSignup::create([
            'campaign_id' => $request->campaign_id,
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'message'     => $request->message,
            'status'      => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Your volunteer application has been submitted successfully!');
    }
}
