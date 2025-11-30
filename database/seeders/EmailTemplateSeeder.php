<?php 

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome',
                'subject' => 'Welcome to DonorLink, {{name}}!',
                'body' => "Hi {{name}},\n\nWelcome to DonorLink! We're thrilled to have you join our community of changemakers.\n\nYour account has been successfully created. You can now:\n- Browse inspiring campaigns\n- Make donations to causes you care about\n- Track your impact\n\nGet started: {{login_url}}\n\nBest regards,\nThe DonorLink Team",
                'variables' => ['name', 'login_url'],
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset Your DonorLink Password',
                'body' => "Hi {{name}},\n\nWe received a request to reset your password. Click the link below to create a new password:\n\n{{reset_url}}\n\nThis link will expire in 60 minutes.\n\nIf you didn't request this, please ignore this email.\n\nBest regards,\nThe DonorLink Team",
                'variables' => ['name', 'reset_url'],
            ],
            [
                'name' => 'Donation Receipt',
                'slug' => 'donation-receipt',
                'subject' => 'Thank You for Your Donation!',
                'body' => "Hi {{donor_name}},\n\nThank you for your generous donation of {{amount}} {{currency}} to {{campaign_title}}!\n\nTransaction ID: {{transaction_id}}\nDate: {{date}}\nPlatform Fee: {{platform_fee}}\nNet Amount: {{net_amount}}\n\nYour contribution is making a real difference. The campaign creator has been notified of your support.\n\nView Campaign: {{campaign_url}}\n\nWith gratitude,\nThe DonorLink Team",
                'variables' => ['donor_name', 'amount', 'currency', 'campaign_title', 'transaction_id', 'date', 'platform_fee', 'net_amount', 'campaign_url'],
            ],
            [
                'name' => 'Campaign Approved',
                'slug' => 'campaign-approved',
                'subject' => 'Your Campaign Has Been Approved!',
                'body' => "Hi {{recipient_name}},\n\nGreat news! Your campaign \"{{campaign_title}}\" has been approved and is now live on DonorLink.\n\nYour campaign is now visible to all donors and ready to receive support.\n\nView Your Campaign: {{campaign_url}}\nManage Campaign: {{dashboard_url}}\n\nTips for success:\n- Share your campaign on social media\n- Post regular updates\n- Engage with your donors\n\nBest of luck!\nThe DonorLink Team",
                'variables' => ['recipient_name', 'campaign_title', 'campaign_url', 'dashboard_url'],
            ],
            [
                'name' => 'Campaign Rejected',
                'slug' => 'campaign-rejected',
                'subject' => 'Update on Your Campaign Submission',
                'body' => "Hi {{recipient_name}},\n\nThank you for submitting your campaign \"{{campaign_title}}\" to DonorLink.\n\nUnfortunately, we're unable to approve this campaign at this time.\n\nReason: {{rejection_reason}}\n\nYou can edit and resubmit your campaign: {{edit_url}}\n\nIf you have questions, please contact us at support@donorlink.com\n\nBest regards,\nThe DonorLink Team",
                'variables' => ['recipient_name', 'campaign_title', 'rejection_reason', 'edit_url'],
            ],
            [
                'name' => 'Verification Approved',
                'slug' => 'verification-approved',
                'subject' => 'Your Account Has Been Verified!',
                'body' => "Hi {{name}},\n\nCongratulations! Your recipient account has been verified.\n\nYou can now:\n- Create public campaigns\n- Receive donations\n- Build your supporter community\n\nCreate Your First Campaign: {{create_campaign_url}}\n\nBest regards,\nThe DonorLink Team",
                'variables' => ['name', 'create_campaign_url'],
            ],
            [
                'name' => 'Campaign Update Notification',
                'slug' => 'campaign-update',
                'subject' => 'New Update from {{campaign_title}}',
                'body' => "Hi {{follower_name}},\n\n{{campaign_title}} has posted a new update!\n\n{{update_title}}\n\n{{update_preview}}\n\nRead Full Update: {{update_url}}\n\nYou're receiving this because you're following this campaign.\n\nBest regards,\nThe DonorLink Team",
                'variables' => ['follower_name', 'campaign_title', 'update_title', 'update_preview', 'update_url'],
            ],
            [
                'name' => 'Recurring Donation Processed',
                'slug' => 'recurring-donation',
                'subject' => 'Your Recurring Donation Has Been Processed',
                'body' => "Hi {{donor_name}},\n\nYour recurring donation of {{amount}} {{currency}} to {{campaign_title}} has been processed successfully.\n\nTransaction ID: {{transaction_id}}\nNext Payment: {{next_payment_date}}\n\nManage Your Recurring Donations: {{manage_url}}\n\nThank you for your continued support!\n\nThe DonorLink Team",
                'variables' => ['donor_name', 'amount', 'currency', 'campaign_title', 'transaction_id', 'next_payment_date', 'manage_url'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}