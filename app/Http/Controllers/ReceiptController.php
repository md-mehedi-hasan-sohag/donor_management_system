<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Show inbox
     */
    public function index()
    {
        $receipts = Receipt::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('receipts.index', compact('receipts'));
    }

    /**
     * Generate receipt from campaign page
     */
    public function store(Campaign $campaign)
    {
        Receipt::create([
            'user_id'     => Auth::id(),
            'campaign_id' => $campaign->id,
            'subject'     => 'Donation Receipt - DonorLink',
            'body'        => nl2br(
                "Thank you for your support!\n\n" .
                "This is a demo email receipt.\n\n" .
                "Campaign: {$campaign->title}\n" .
                "Amount: ৳1,000\n" .
                "Payment Method: Demo\n\n" .
                "— DonorLink Team"
            ),
            'is_read'     => false,
        ]);

        return redirect()
            ->route('receipts.index')
            ->with('success', 'Receipt generated successfully!');
    }

    /**
     * Show single receipt
     */
    public function show(Receipt $receipt)
    {
        abort_unless($receipt->user_id === Auth::id(), 403);

        if (!$receipt->is_read) {
            $receipt->update(['is_read' => true]);
        }

        return view('receipts.show', compact('receipt'));
    }
}
