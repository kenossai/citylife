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

        // Enhanced sample schedule data in the new format
        $sampleSchedule = [
            // Leadership
            'Preaching' => [
                '2025-10-06' => 'Jim',
                '2025-10-13' => 'Terence',
                '2025-10-20' => 'Jim',
                '2025-10-27' => 'James',
            ],
            'Leading' => [
                '2025-10-06' => 'JB',
                '2025-10-13' => 'Gail',
                '2025-10-20' => 'JC',
                '2025-10-27' => 'Sofia',
            ],

            // Worship Team
            'Worship Leader' => [
                '2025-10-06' => 'JB',
                '2025-10-13' => 'JC',
                '2025-10-20' => 'Sofia',
                '2025-10-27' => 'JB',
            ],
            'Lead/Second Guitar' => [
                '2025-10-06' => 'JC',
                '2025-10-13' => 'Brian G',
                '2025-10-20' => 'JC',
                '2025-10-27' => 'Gail',
            ],
            'Bass Guitar' => [
                '2025-10-06' => 'Brian G',
                '2025-10-13' => 'Brian G',
                '2025-10-20' => 'Brian G',
                '2025-10-27' => 'Brian G',
            ],
            'Acoustic Guitar' => [
                '2025-10-06' => 'Gail',
                '2025-10-13' => 'JC',
                '2025-10-20' => 'Gail',
                '2025-10-27' => 'JC',
            ],
            'Piano 1' => [
                '2025-10-06' => 'JB',
                '2025-10-13' => 'JB',
                '2025-10-20' => 'JB',
                '2025-10-27' => 'JB',
            ],
            'Piano 2' => [
                '2025-10-06' => 'Jessy',
                '2025-10-13' => 'VW',
                '2025-10-20' => 'Jessy',
                '2025-10-27' => 'Vivienne',
            ],
            'Drums' => [
                '2025-10-06' => 'Jonathan',
                '2025-10-13' => 'Simeon',
                '2025-10-20' => 'Jonathan',
                '2025-10-27' => 'Simeon',
            ],
            'Singers Team' => [
                '2025-10-06' => 'Funmi, Stephanie, Yvonne',
                '2025-10-13' => 'Stephanie, Youth Team, Vivienne',
                '2025-10-20' => 'Funmi, Mercy, Yvonne',
                '2025-10-27' => 'Stephanie, Mercy, Vivienne',
            ],

            // Technical Team
            'TL For The Day' => [
                '2025-10-06' => 'Ken',
                '2025-10-13' => 'Asher',
                '2025-10-20' => 'Tabitha',
                '2025-10-27' => 'Ken',
            ],
            'Media(Kelham)' => [
                '2025-10-06' => 'Mounisha',
                '2025-10-13' => 'Linas',
                '2025-10-20' => 'Tabitha',
                '2025-10-27' => 'Peter',
            ],
            'PA(Kelham)' => [
                '2025-10-06' => 'Ken',
                '2025-10-13' => 'Raunak',
                '2025-10-20' => 'Ken',
                '2025-10-27' => 'Linas',
            ],
            'Visual(Kelham)' => [
                '2025-10-06' => 'Chidera',
                '2025-10-13' => 'Asher',
                '2025-10-20' => 'Nidhsesh',
                '2025-10-27' => 'Ken',
            ],
            'Training/Shadow' => [
                '2025-10-06' => 'Fin',
                '2025-10-13' => 'Edward',
                '2025-10-20' => 'Zach',
                '2025-10-27' => 'Fin',
            ],
        ];

        Rota::create([
            'title' => 'October 2025 Ministry Rota - Kelham Island',
            'description' => 'Complete ministry rota covering all departments for October services',
            'departments' => ['worship', 'technical', 'preacher'],
            'start_date' => '2025-10-06',
            'end_date' => '2025-10-27',
            'schedule_data' => $sampleSchedule,
            'notes' => 'Enhanced ministry rota with proper role categorization and member assignments',
            'is_published' => true,
            'created_by' => $user->id,
        ]);

        $this->command->info('Enhanced sample rota created successfully!');
    }
}
