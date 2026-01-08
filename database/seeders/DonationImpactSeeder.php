<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationImpact;

class DonationImpactSeeder extends Seeder
{
    public function run(): void
    {
        DonationImpact::truncate();

        DonationImpact::insert([
            [
                'min_amount' => 1,
                'max_amount' => 199,
                'title' => 'Small help, big love ❤️',
                'message' => 'Your donation still matters. Every step moves us forward.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 200,
                'max_amount' => 499,
                'title' => 'Meals supported 🍛',
                'message' => 'This amount can help provide meals for people in need.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 500,
                'max_amount' => 999,
                'title' => 'Education support 🎒',
                'message' => 'Your donation can support school supplies and learning materials.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 1000,
                'max_amount' => null,
                'title' => 'Major impact 🏥',
                'message' => 'This amount can help cover urgent needs like medical support and essentials.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
