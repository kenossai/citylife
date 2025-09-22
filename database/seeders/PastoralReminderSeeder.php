<?php

namespace Database\Seeders;

use App\Models\PastoralReminder;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PastoralReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some members to create reminders for
        $members = Member::take(10)->get();

        if ($members->isEmpty()) {
            $this->command->info('No members found. Please seed members first.');
            return;
        }

        foreach ($members as $member) {
            // Create birthday reminder if member has date of birth
            if ($member->date_of_birth) {
                PastoralReminder::create([
                    'member_id' => $member->id,
                    'reminder_type' => 'birthday',
                    'reminder_date' => $member->date_of_birth,
                    'days_before_reminder' => 7,
                    'is_annual' => true,
                    'is_active' => true,
                    'notification_recipients' => [
                        'admin@citylifecc.com',
                        'pastor@citylifecc.com'
                    ],
                ]);
            }

            // Create membership anniversary reminder if member has membership date
            if ($member->membership_date) {
                PastoralReminder::create([
                    'member_id' => $member->id,
                    'reminder_type' => 'membership_anniversary',
                    'reminder_date' => $member->membership_date,
                    'days_before_reminder' => 7,
                    'is_annual' => true,
                    'is_active' => true,
                    'year_created' => $member->membership_date->year,
                    'notification_recipients' => [
                        'admin@citylifecc.com',
                        'pastor@citylifecc.com'
                    ],
                ]);
            }

            // Create baptism anniversary reminder if member has baptism date
            if ($member->baptism_date) {
                PastoralReminder::create([
                    'member_id' => $member->id,
                    'reminder_type' => 'baptism_anniversary',
                    'reminder_date' => $member->baptism_date,
                    'days_before_reminder' => 7,
                    'is_annual' => true,
                    'is_active' => true,
                    'year_created' => $member->baptism_date->year,
                    'notification_recipients' => [
                        'admin@citylifecc.com',
                        'pastor@citylifecc.com'
                    ],
                ]);
            }
        }

        // Create some sample reminders with dates coming up soon for testing
        $testMember = $members->first();

        // Birthday coming up in 5 days
        PastoralReminder::create([
            'member_id' => $testMember->id,
            'reminder_type' => 'birthday',
            'reminder_date' => now()->addDays(5),
            'days_before_reminder' => 7,
            'is_annual' => true,
            'is_active' => true,
            'notification_recipients' => [
                'admin@citylifecc.com',
                'pastor@citylifecc.com'
            ],
        ]);

        // Custom reminder for pastoral visit
        PastoralReminder::create([
            'member_id' => $testMember->id,
            'reminder_type' => 'custom',
            'title' => 'Follow-up Visit',
            'description' => 'Scheduled follow-up visit after prayer request',
            'reminder_date' => now()->addDays(3),
            'days_before_reminder' => 1,
            'is_annual' => false,
            'is_active' => true,
            'notification_recipients' => [
                'pastor@citylifecc.com'
            ],
            'custom_message' => [
                'message' => 'Remember to follow up with {full_name} regarding their prayer request on {date}.'
            ],
        ]);

        $this->command->info('Pastoral reminders seeded successfully!');
    }
}
