<?php 

namespace Database\Seeders;

use App\Models\User;
use App\Models\RecipientVerification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@donorlink.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'verification_status' => 'verified',
            'account_status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        // Verified Recipients
        $recipient1 = User::create([
            'name' => 'Hope Foundation',
            'email' => 'contact@hopefoundation.org',
            'password' => Hash::make('password'),
            'role' => 'recipient',
            'phone' => '+1234567890',
            'address' => '123 Charity Lane, New York, NY 10001',
            'verification_status' => 'verified',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        RecipientVerification::create([
            'user_id' => $recipient1->id,
            'recipient_type' => 'organization',
            'organization_name' => 'Hope Foundation',
            'registration_documents_path' => 'verification/hope-foundation-reg.pdf',
            'tax_exempt_status_path' => 'verification/hope-foundation-tax.pdf',
            'primary_contact_name' => 'John Smith',
            'primary_contact_id_path' => 'verification/john-smith-id.pdf',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $recipient2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@email.com',
            'password' => Hash::make('password'),
            'role' => 'recipient',
            'phone' => '+1987654321',
            'address' => '456 Community St, Los Angeles, CA 90001',
            'verification_status' => 'verified',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        RecipientVerification::create([
            'user_id' => $recipient2->id,
            'recipient_type' => 'individual',
            'government_id_path' => 'verification/sarah-johnson-id.pdf',
            'proof_of_address_path' => 'verification/sarah-johnson-address.pdf',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        // Pending Verification Recipient
        $recipient3 = User::create([
            'name' => 'Green Earth Initiative',
            'email' => 'info@greenearth.org',
            'password' => Hash::make('password'),
            'role' => 'recipient',
            'verification_status' => 'pending',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        RecipientVerification::create([
            'user_id' => $recipient3->id,
            'recipient_type' => 'organization',
            'organization_name' => 'Green Earth Initiative',
            'registration_documents_path' => 'verification/green-earth-reg.pdf',
            'primary_contact_name' => 'Emily Green',
            'primary_contact_id_path' => 'verification/emily-green-id.pdf',
            'status' => 'pending',
        ]);

        // Donor Users
        $donors = [
            ['name' => 'Michael Chen', 'email' => 'michael.chen@email.com'],
            ['name' => 'Emma Williams', 'email' => 'emma.williams@email.com'],
            ['name' => 'David Brown', 'email' => 'david.brown@email.com'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.anderson@email.com'],
            ['name' => 'James Taylor', 'email' => 'james.taylor@email.com'],
            ['name' => 'Sophia Martinez', 'email' => 'sophia.martinez@email.com'],
            ['name' => 'Robert Lee', 'email' => 'robert.lee@email.com'],
            ['name' => 'Olivia Garcia', 'email' => 'olivia.garcia@email.com'],
            ['name' => 'William Davis', 'email' => 'william.davis@email.com'],
            ['name' => 'Ava Rodriguez', 'email' => 'ava.rodriguez@email.com'],
        ];

        foreach ($donors as $donor) {
            User::create([
                'name' => $donor['name'],
                'email' => $donor['email'],
                'password' => Hash::make('password'),
                'role' => 'donor',
                'verification_status' => 'unverified',
                'account_status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
    }
}