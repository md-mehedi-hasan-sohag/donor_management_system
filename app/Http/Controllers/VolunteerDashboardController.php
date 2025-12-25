<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\VolunteerSignup;

class VolunteerDashboardController extends Controller
{
    /**
     * Show volunteer dashboard for logged-in user
     */
    public function index()
    {
        $volunteerSignups = VolunteerSignup::with('campaign')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('volunteer.dashboard', compact('volunteerSignups'));
    }
}
