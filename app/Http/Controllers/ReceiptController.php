<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function store(Campaign $campaign)
    {
        $user = auth()->user();

        // ✅ CHECK: user must have a completed donation
        $hasDonated = $campaign->donations()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasDonated) {
            abort(403, 'Only donors can generate receipts.');
        }

        // ✅ Prevent duplicate receipt
        $alreadyGenerated = Receipt::where('user_id', $user->id)
            ->where('campaign_id', $campaign->id)
            ->exists();

        if ($alreadyGenerated) {
            return back()->with('info', 'Receipt already generated.');
        }

        // ✅ Create receipt
        Receipt::create([
            'user_id'     => $user->id,
            'campaign_id' => $campaign->id,
            'subject'     => 'Donation Receipt Generated',
            'body'        => "Receipt has been generated for the campaign '{$campaign->title}'.",
        ]);

        return back()->with('success', 'Receipt generated successfully.');
    }
}
