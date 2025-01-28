<?php

namespace Database\Seeders;

use App\Models\DynamicPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DynamicPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DynamicPage::insert([
            [
                "page_title" => "Privacy and Policy",
                "page_slug" => "privacy-and-policy",
                "page_content" => "Welcome to [Your Platform Name]! Your privacy is extremely important to us. This Privacy Policy explains how we collect, use, and protect your personal data when you use our services. \n\n1. **Data Collection**: We collect personal information such as your name, email address, payment details, and professional portfolio to provide our services efficiently. \n\n2. **Data Usage**: Your data is used to create your account, match you with suitable projects or professionals, process transactions, and improve our platform. \n\n3. **Third-Party Sharing**: We never share your data with third parties without your consent, except as required by law. \n\n4. **Data Security**: We employ industry-standard measures to ensure your data remains secure. \n\n5. **Your Rights**: You have the right to access, modify, or delete your data. You can manage your preferences in your account settings or contact our support team. \n\nFor a detailed overview, please visit the full Privacy Policy on our website or contact us with any questions.",
            ],
            [
                "page_title" => "Terms and Conditions",
                "page_slug" => "terms-and-conditions",
                "page_content" => "By accessing or using [Your Platform Name], you agree to comply with our Terms and Conditions. These terms are designed to ensure a safe, fair, and professional experience for all users. \n\n1. **Account Creation**: Users must provide accurate and complete information when creating an account. Misrepresentation may result in account suspension or termination. \n\n2. **Prohibited Activities**: Users may not engage in fraud, harassment, or any illegal activities on the platform. We reserve the right to investigate and act upon violations. \n\n3. **Payment Policies**: Payments for services rendered must be processed through our platform. Any off-platform transactions are prohibited and may result in account suspension. \n\n4. **Dispute Resolution**: In the event of a disagreement between clients and freelancers, our dispute resolution process will help mediate the issue fairly. \n\n5. **Platform Rights**: We reserve the right to modify, suspend, or terminate any part of the service at any time. \n\nBy using our platform, you confirm that you have read and agree to these terms. Please contact our support team for further clarifications.",
            ],
        ]);
        
    }
}
