<?php 

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;
use App\Models\DonationTeam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = Campaign::where('status', 'active')->get();
        $donors = User::where('role', 'donor')->get();

        if ($campaigns->isEmpty() || $donors->isEmpty()) {
            return;
        }

        // Create some donation teams
        $teams = [];
        foreach ($campaigns->take(3) as $campaign) {
            $team = DonationTeam::create([
                'campaign_id' => $campaign->id,
                'created_by' => $donors->random()->id,
                'name' => 'Team ' . $campaign->title,
                'slug' => Str::slug('Team ' . $campaign->title) . '-' . Str::random(6),
                'description' => 'Join us in supporting this amazing cause!',
                'team_goal' => $campaign->goal_amount * 0.3,
                'total_raised' => 0,
            ]);

            // Add team members
            $teamMembers = $donors->random(rand(3, 6));
            foreach ($teamMembers as $index => $member) {
                $team->members()->attach($member->id, [
                    'role' => $index === 0 ? 'leader' : 'member',
                ]);
            }

            $teams[] = $team;
        }

        // Create donations
        foreach ($campaigns as $campaign) {
            $numDonations = rand(10, 30);
            
            for ($i = 0; $i < $numDonations; $i++) {
                $donor = $donors->random();
                $isAnonymous = rand(0, 10) < 2; // 20% anonymous
                $donationType = rand(0, 10) < 8 ? 'monetary' : 'in_kind'; // 80% monetary
                
                $donationData = [
                    'campaign_id' => $campaign->id,
                    'user_id' => $donor->id,
                    'donor_name' => $donor->name,
                    'donation_type' => $donationType,
                    'is_anonymous' => $isAnonymous,
                    'payment_status' => 'completed',
                    'payment_completed_at' => now()->subDays(rand(1, 30)),
                    'created_at' => now()->subDays(rand(1, 30)),
                ];

                if ($donationType === 'monetary') {
                    $amount = [25, 50, 100, 250, 500, 1000][rand(0, 5)];
                    $platformFeePercentage = 2.5;
                    $platformFee = $amount * ($platformFeePercentage / 100);
                    $netAmount = $amount - $platformFee;

                    $donationData = array_merge($donationData, [
                        'amount' => $amount,
                        'currency' => 'USD',
                        'platform_fee' => $platformFee,
                        'net_amount' => $netAmount,
                        'payment_method' => ['stripe', 'paypal'][rand(0, 1)],
                        'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                    ]);

                    // Some donations are recurring
                    if (rand(0, 10) < 2) { // 20% recurring
                        $donationData['is_recurring'] = true;
                        $donationData['recurring_frequency'] = ['monthly', 'quarterly'][rand(0, 1)];
                        $donationData['recurring_active'] = true;
                        $donationData['next_recurring_date'] = now()->addMonth();
                    }

                    // Some donations are part of teams
                    if (!empty($teams) && rand(0, 10) < 3) { // 30% team donations
                        $relevantTeam = collect($teams)->firstWhere('campaign_id', $campaign->id);
                        if ($relevantTeam) {
                            $donationData['team_id'] = $relevantTeam->id;
                        }
                    }
                } else {
                    // In-kind donation
                    $items = [
                        'Books and school supplies',
                        'Blankets and warm clothing',
                        'Non-perishable food items',
                        'Medical supplies',
                        'Pet food and toys',
                    ];
                    $donationData['in_kind_items'] = $items[rand(0, count($items) - 1)];
                }

                // Add optional message
                if (rand(0, 10) < 4) { // 40% with message
                    $messages = [
                        'Happy to help!',
                        'Keep up the great work!',
                        'Wishing you all the best.',
                        'Thank you for what you do.',
                        'Proud to support this cause.',
                    ];
                    $donationData['message'] = $messages[rand(0, count($messages) - 1)];
                }

                Donation::create($donationData);
            }
        }

        // Update team totals
        foreach ($teams as $team) {
            $total = $team->donations()
                ->where('payment_status', 'completed')
                ->sum('amount');
            $team->update(['total_raised' => $total]);
        }

        // Create some volunteer signups
        foreach ($campaigns->where('accepts_volunteers', true)->take(4) as $campaign) {
            $volunteers = $donors->random(rand(3, 8));
            
            foreach ($volunteers as $volunteer) {
                $campaign->volunteers()->create([
                    'user_id' => $volunteer->id,
                    'name' => $volunteer->name,
                    'email' => $volunteer->email,
                    'phone' => '+1' . rand(1000000000, 9999999999),
                    'message' => 'I would love to volunteer for this campaign!',
                    'status' => ['pending', 'approved'][rand(0, 1)],
                ]);
            }
        }

        // Award some badges
        $firstDonationBadge = \App\Models\DonorBadge::where('slug', 'first-donation')->first();
        $generousSupporterBadge = \App\Models\DonorBadge::where('slug', 'generous-supporter')->first();

        foreach ($donors as $donor) {
            $donationCount = $donor->donations()->where('payment_status', 'completed')->count();
            
            if ($donationCount >= 1 && $firstDonationBadge) {
                $donor->badges()->syncWithoutDetaching([$firstDonationBadge->id]);
            }
            
            if ($donationCount >= 10 && $generousSupporterBadge) {
                $donor->badges()->syncWithoutDetaching([$generousSupporterBadge->id]);
            }
        }
    }
}