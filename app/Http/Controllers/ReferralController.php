<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function invite()
    {
        if (auth()->user()->role !== 'donor') {
        abort(403, 'Only donors can access referrals');
        }
        $latest = Referral::where('referrer_id', auth()->id())
            ->latest()
            ->first();

        return view('referrals.invite', compact('latest'));
    }

    public function generate()
    {
        if (auth()->user()->role !== 'donor') {
        abort(403, 'Only donors can generate referral links');
    }
        $code = strtoupper(Str::random(8));

        Referral::create([
            'referrer_id' => auth()->id(),
            'code' => $code,
            'status' => 'pending',
        ]);

        return redirect()->route('referrals.invite')->with('success', 'Referral link generated!');
    }
}
