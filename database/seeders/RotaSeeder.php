<?php

namespace Database\Seeders;

use App\Models\Rota;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RotaSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first user as creator
        $user = User::first();

        if (!$user) {
            $this->command->info('No users found. Please create a user first.');
            return;
        }

        // Sample schedule data for worship rota
        $sampleSchedule = [
            '2025-08-17' => [
                'Worship Leader' => 'JB',
                'Lead Vocalist' => 'Gail',
                'Guitarist' => 'JC',
                'Bassist' => 'Brian G',
                'Drummer' => 'Jonathan',
                'Keyboardist' => 'JB',
            ],
            '2025-08-24' => [
                'Worship Leader' => 'JC',
                'Lead Vocalist' => 'Stephanie',
                'Guitarist' => 'Gail',
                'Bassist' => 'Brian G',
                'Drummer' => 'Simeon',
                'Keyboardist' => 'JB',
            ],
        ];

        Rota::create([
            'title' => 'August 2025 Worship Rota',
            'department_type' => 'worship',
            'start_date' => '2025-08-17',
            'end_date' => '2025-08-31',
            'schedule_data' => $sampleSchedule,
            'notes' => 'Sample worship rota for testing Excel export functionality',
            'is_published' => true,
            'created_by' => $user->id,
        ]);

        $this->command->info('Sample rota created successfully!');
    }
}
