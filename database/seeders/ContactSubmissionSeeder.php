<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactSubmission;

class ContactSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $submissions = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '555-123-4567',
                'subject' => 'Prayer Request',
                'message' => 'Please pray for my family during this difficult time. My father is in the hospital and we could use all the prayers we can get.',
                'gdpr_consent' => true,
                'ip_address' => '192.168.1.1',
                'status' => 'new',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@email.com',
                'phone' => '555-987-6543',
                'subject' => 'Volunteer Opportunities',
                'message' => 'I would like to get involved in church activities and volunteer work. What opportunities are available for new members?',
                'gdpr_consent' => true,
                'ip_address' => '192.168.1.2',
                'status' => 'in_progress',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@email.com',
                'phone' => '555-456-7890',
                'subject' => 'Event Information',
                'message' => 'Can you provide more information about the upcoming Easter celebration? What time does it start and should we bring anything?',
                'gdpr_consent' => true,
                'ip_address' => '192.168.1.3',
                'status' => 'responded',
                'responded_at' => now()->subDay(),
                'responded_by' => 1,
                'admin_notes' => 'Sent detailed information about Easter service via email.',
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@email.com',
                'subject' => 'General Inquiry',
                'message' => 'I am new to the area and looking for a church home. Could you tell me more about your services and community?',
                'gdpr_consent' => true,
                'ip_address' => '192.168.1.4',
                'status' => 'new',
                'created_at' => now()->subHours(6),
            ],
            [
                'name' => 'Lisa Williams',
                'email' => 'lisa.williams@email.com',
                'phone' => '555-321-0987',
                'subject' => 'Pastoral Care',
                'message' => 'I would like to schedule a meeting with the pastor to discuss some personal matters. When would be a good time?',
                'gdpr_consent' => true,
                'ip_address' => '192.168.1.5',
                'status' => 'new',
                'created_at' => now()->subHours(2),
            ],
        ];

        foreach ($submissions as $submission) {
            ContactSubmission::create($submission);
        }
    }
}
