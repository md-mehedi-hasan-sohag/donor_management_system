<?php

namespace App\Http\Controllers;

use App\Models\DonationImpact;

class DonationImpactController extends Controller
{
    public function index()
    {
        $impacts = DonationImpact::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('min_amount')
            ->get();

        return view('donations.impacts', compact('impacts'));
    }
}
