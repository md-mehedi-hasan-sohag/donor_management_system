<?php

namespace App\Http\Controllers;

use App\Models\FraudReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FraudReportController extends Controller
{
    // Store fraud report (User-side only)
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'reason'      => 'required|string|min:10',
        ]);

        FraudReport::create([
            'campaign_id' => $request->campaign_id,
            'reported_by' => auth()->id(),
            'reason'      => $request->reason,
            'description' => $request->reason, // 👈 FIX
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Thank you. The campaign has been reported.');
    }
}