<?php 

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $recipients = User::where('role', 'recipient')
            ->where('verification_status', 'verified')
            ->get();
        
        $categories = Category::all();
        $admin = User::where('role', 'admin')->first();

        if ($recipients->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $campaigns = [
            [
                'title' => 'School Supplies for Rural Children',
                'description' => 'Help provide essential school supplies to children in rural communities. Your donation will provide books, notebooks, pens, and other learning materials to students who lack access to basic educational resources.',
                'goal_amount' => 5000,
                'current_amount' => 3250,
                'end_date' => now()->addDays(45),
                'location' => 'Rural Districts, State',
                'is_urgent' => false,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => true,
                'in_kind_needs' => 'Books, Notebooks, Pens, Pencils, Backpacks',
                'status' => 'active',
            ],
            [
                'title' => 'Emergency Medical Treatment Fund',
                'description' => 'Urgent appeal for medical treatment costs. A young patient requires immediate surgery and ongoing treatment. Every contribution brings hope and healing.',
                'goal_amount' => 15000,
                'current_amount' => 8900,
                'end_date' => now()->addDays(20),
                'location' => 'City Hospital, Major City',
                'is_urgent' => true,
                'is_verified' => true,
                'accepts_volunteers' => false,
                'accepts_in_kind' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Community Food Bank Expansion',
                'description' => 'Support our mission to fight hunger by helping expand our community food bank. We aim to serve 500 additional families each month with nutritious meals.',
                'goal_amount' => 10000,
                'current_amount' => 6500,
                'end_date' => now()->addDays(60),
                'location' => 'Downtown Community Center',
                'is_urgent' => false,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => true,
                'in_kind_needs' => 'Non-perishable food items, Storage containers',
                'status' => 'active',
            ],
            [
                'title' => 'Clean Water Project for Villages',
                'description' => 'Bring clean, safe drinking water to remote villages. Your donation will help install water filtration systems and wells, improving health and quality of life for entire communities.',
                'goal_amount' => 25000,
                'current_amount' => 12000,
                'end_date' => now()->addDays(90),
                'location' => 'Remote Villages',
                'is_urgent' => false,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Animal Shelter Renovation',
                'description' => 'Help us renovate our animal shelter to provide better care for rescued animals. We need to upgrade facilities, medical equipment, and create more space for animals in need.',
                'goal_amount' => 8000,
                'current_amount' => 4200,
                'end_date' => now()->addDays(50),
                'location' => 'Local Animal Shelter',
                'is_urgent' => false,
                'is_verified' => false,
                'accepts_volunteers' => true,
                'accepts_in_kind' => true,
                'in_kind_needs' => 'Pet food, Blankets, Toys, Cleaning supplies',
                'status' => 'active',
            ],
            [
                'title' => 'Youth Arts Program',
                'description' => 'Fund art supplies and workshops for underprivileged youth. Give young people the opportunity to explore creativity and develop artistic skills through professional instruction.',
                'goal_amount' => 6000,
                'current_amount' => 1800,
                'end_date' => now()->addDays(75),
                'location' => 'Community Arts Center',
                'is_urgent' => false,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => true,
                'in_kind_needs' => 'Art supplies, Canvas, Paint, Brushes',
                'status' => 'active',
            ],
            [
                'title' => 'Disaster Relief - Flood Victims',
                'description' => 'Immediate assistance needed for families affected by recent flooding. Provide emergency shelter, food, clothing, and medical supplies to displaced families.',
                'goal_amount' => 20000,
                'current_amount' => 15600,
                'end_date' => now()->addDays(15),
                'location' => 'Flood-Affected Region',
                'is_urgent' => true,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => true,
                'in_kind_needs' => 'Blankets, Clothing, Water bottles, First aid supplies',
                'status' => 'active',
            ],
            [
                'title' => 'Senior Care Program',
                'description' => 'Support our senior care program providing meals, companionship, and medical assistance to elderly community members who live alone.',
                'goal_amount' => 7500,
                'current_amount' => 3100,
                'end_date' => now()->addDays(40),
                'location' => 'Senior Care Center',
                'is_urgent' => false,
                'is_verified' => true,
                'accepts_volunteers' => true,
                'accepts_in_kind' => false,
                'status' => 'active',
            ],
        ];

        foreach ($campaigns as $index => $campaignData) {
            $recipient = $recipients->random();
            $category = $categories->random();

            $campaign = Campaign::create([
                'user_id' => $recipient->id,
                'category_id' => $category->id,
                'title' => $campaignData['title'],
                'slug' => Str::slug($campaignData['title']) . '-' . Str::random(6),
                'description' => $campaignData['description'],
                'image_path' => 'campaigns/default-' . ($index % 5 + 1) . '.jpg',
                'goal_amount' => $campaignData['goal_amount'],
                'current_amount' => $campaignData['current_amount'],
                'end_date' => $campaignData['end_date'],
                'location' => $campaignData['location'],
                'is_urgent' => $campaignData['is_urgent'],
                'is_verified' => $campaignData['is_verified'],
                'accepts_volunteers' => $campaignData['accepts_volunteers'],
                'accepts_in_kind' => $campaignData['accepts_in_kind'],
                'in_kind_needs' => $campaignData['in_kind_needs'] ?? null,
                'status' => $campaignData['status'],
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(rand(5, 30)),
                'total_donors' => rand(15, 150),
                'followers_count' => rand(20, 200),
            ]);

            // Add some campaign updates
            if (rand(0, 1)) {
                $campaign->updates()->create([
                    'update_type' => 'progress',
                    'title' => 'Thank you for your support!',
                    'content' => 'We are grateful for all the donations received so far. Your generosity is making a real difference in our community.',
                ]);
            }

            // Add milestone updates for campaigns that reached milestones
            $progress = $campaign->progressPercentage();
            if ($progress >= 25 && $progress < 50) {
                $campaign->updates()->create([
                    'update_type' => 'milestone',
                    'title' => '25% Milestone Reached!',
                    'content' => 'Amazing! We\'ve reached 25% of our goal. Thank you to all our supporters!',
                    'milestone_percentage' => 25,
                ]);
            } elseif ($progress >= 50 && $progress < 75) {
                $campaign->updates()->createMany([
                    [
                        'update_type' => 'milestone',
                        'title' => '25% Milestone Reached!',
                        'content' => 'Amazing! We\'ve reached 25% of our goal.',
                        'milestone_percentage' => 25,
                        'created_at' => now()->subDays(10),
                    ],
                    [
                        'update_type' => 'milestone',
                        'title' => 'Halfway There - 50% Milestone!',
                        'content' => 'We\'re halfway to our goal! Your support means everything.',
                        'milestone_percentage' => 50,
                        'created_at' => now()->subDays(5),
                    ],
                ]);
            }
        }

        // Add a pending campaign
        Campaign::create([
            'user_id' => $recipients->first()->id,
            'category_id' => $categories->first()->id,
            'title' => 'New Initiative Awaiting Approval',
            'slug' => Str::slug('New Initiative Awaiting Approval') . '-' . Str::random(6),
            'description' => 'This campaign is pending admin approval.',
            'goal_amount' => 5000,
            'current_amount' => 0,
            'end_date' => now()->addDays(60),
            'location' => 'Various Locations',
            'is_urgent' => false,
            'is_verified' => false,
            'accepts_volunteers' => false,
            'accepts_in_kind' => false,
            'status' => 'pending',
        ]);
    }
}