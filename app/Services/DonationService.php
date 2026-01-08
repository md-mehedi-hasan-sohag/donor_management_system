<?php

namespace App\Services;

use App\Models\DonationImpact;

class DonationImpactService
{
    public function getImpactForAmount(int $amount): ?DonationImpact
    {
        return DonationImpact::where('is_active', true)
            ->where('min_amount', '<=', $amount)
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')
                  ->orWhere('max_amount', '>=', $amount);
            })
            ->orderBy('min_amount', 'desc')
            ->first();
    }
}
