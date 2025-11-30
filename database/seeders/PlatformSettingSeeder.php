<?php 

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'platform_fee_percentage',
                'value' => '2.5',
                'type' => 'string',
                'description' => 'Platform service fee percentage on donations',
            ],
            [
                'key' => 'enable_leaderboard',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable or disable the donor leaderboard',
            ],
            [
                'key' => 'enable_badges',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable or disable the badge system',
            ],
            [
                'key' => 'max_campaign_duration_days',
                'value' => '365',
                'type' => 'integer',
                'description' => 'Maximum duration for campaigns in days',
            ],
            [
                'key' => 'min_donation_amount',
                'value' => '5',
                'type' => 'string',
                'description' => 'Minimum donation amount in USD',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@donorlink.com',
                'type' => 'string',
                'description' => 'Platform support email address',
            ],
            [
                'key' => 'supported_currencies',
                'value' => json_encode(['USD', 'EUR', 'GBP', 'CAD', 'AUD']),
                'type' => 'json',
                'description' => 'List of supported currencies',
            ],
            [
                'key' => 'auto_approve_campaigns',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Automatically approve campaigns without admin review',
            ],
            [
                'key' => 'enable_social_impact_feed',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Show social impact feed on homepage',
            ],
            [
                'key' => 'milestone_percentages',
                'value' => json_encode([25, 50, 75, 100]),
                'type' => 'json',
                'description' => 'Campaign milestone percentages for automatic updates',
            ],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::create($setting);
        }
    }
}