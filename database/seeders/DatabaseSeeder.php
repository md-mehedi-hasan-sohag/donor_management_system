<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            PlatformSettingSeeder::class,
            EmailTemplateSeeder::class,
            StaticPageSeeder::class,
            DonorBadgeSeeder::class,
            CampaignSeeder::class,
            DonationSeeder::class,
        ]);
    }
}