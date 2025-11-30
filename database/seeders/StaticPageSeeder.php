<?php 

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About DonorLink</h1><p>DonorLink is a comprehensive donor management platform that connects generous donors with verified recipients and meaningful causes.</p><p>Our mission is to make charitable giving transparent, efficient, and impactful.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'How It Works',
                'slug' => 'how-it-works',
                'content' => '<h1>How DonorLink Works</h1><h2>For Donors</h2><p>Browse campaigns, make donations, and track your impact.</p><h2>For Recipients</h2><p>Create campaigns, receive funding, and engage with supporters.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us. This policy outlines how we collect, use, and protect your personal information.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms',
                'content' => '<h1>Terms of Service</h1><p>By using DonorLink, you agree to these terms and conditions.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => '<h1>Frequently Asked Questions</h1><h2>How do I create a campaign?</h2><p>First, verify your recipient account, then click "Create Campaign" from your dashboard.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Contact Us</h1><p>Email: support@donorlink.com</p><p>Phone: +1 (800) 123-4567</p>',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            StaticPage::create($page);
        }
    }
}