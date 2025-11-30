<?php 

namespace Database\Seeders;

use App\Models\DonorBadge;
use Illuminate\Database\Seeder;

class DonorBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Donation',
                'slug' => 'first-donation',
                'description' => 'Made your first donation',
                'icon' => 'gift',
                'criteria_type' => 'donation_count',
                'criteria_value' => 1,
            ],
            [
                'name' => 'Generous Supporter',
                'slug' => 'generous-supporter',
                'description' => 'Made 10 donations',
                'icon' => 'heart',
                'criteria_type' => 'donation_count',
                'criteria_value' => 10,
            ],
            [
                'name' => 'Dedicated Donor',
                'slug' => 'dedicated-donor',
                'description' => 'Made 50 donations',
                'icon' => 'star',
                'criteria_type' => 'donation_count',
                'criteria_value' => 50,
            ],
            [
                'name' => 'Champion of Change',
                'slug' => 'champion-of-change',
                'description' => 'Made 100 donations',
                'icon' => 'trophy',
                'criteria_type' => 'donation_count',
                'criteria_value' => 100,
            ],
            [
                'name' => '$100 Club',
                'slug' => '100-club',
                'description' => 'Donated $100 or more in total',
                'icon' => 'dollar-sign',
                'criteria_type' => 'total_amount',
                'criteria_value' => 100,
            ],
            [
                'name' => '$1000 Club',
                'slug' => '1000-club',
                'description' => 'Donated $1000 or more in total',
                'icon' => 'award',
                'criteria_type' => 'total_amount',
                'criteria_value' => 1000,
            ],
            [
                'name' => 'Monthly Hero',
                'slug' => 'monthly-hero',
                'description' => 'Active recurring donor',
                'icon' => 'calendar',
                'criteria_type' => 'special',
                'criteria_value' => null,
            ],
            [
                'name' => 'Early Supporter',
                'slug' => 'early-supporter',
                'description' => 'Among the first donors to a campaign',
                'icon' => 'zap',
                'criteria_type' => 'special',
                'criteria_value' => null,
            ],
            [
                'name' => 'Team Player',
                'slug' => 'team-player',
                'description' => 'Joined a donation team',
                'icon' => 'users',
                'criteria_type' => 'special',
                'criteria_value' => null,
            ],
            [
                'name' => 'Impact Maker',
                'slug' => 'impact-maker',
                'description' => 'Supported 20 different campaigns',
                'icon' => 'trending-up',
                'criteria_type' => 'special',
                'criteria_value' => 20,
            ],
        ];

        foreach ($badges as $badge) {
            DonorBadge::create($badge);
        }
    }
}