<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::updateOrCreate(
            ['code' => 'BDT'],
            ['name' => 'Bangladeshi Taka', 'rate_to_bdt' => 1, 'is_active' => true]
        );

        Currency::updateOrCreate(
            ['code' => 'USD'],
            ['name' => 'US Dollar', 'rate_to_bdt' => 110, 'is_active' => true]
        );

        Currency::updateOrCreate(
            ['code' => 'EUR'],
            ['name' => 'Euro', 'rate_to_bdt' => 120, 'is_active' => true]
        );
    }
}
