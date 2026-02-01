<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BibleSchoolEvent;
use App\Models\BibleSchoolVideo;
use App\Models\BibleSchoolAudio;
use App\Models\BibleSchoolAccessCode;
use App\Models\BibleSchoolSpeaker;

class BibleSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample speakers
        $speaker1 = BibleSchoolSpeaker::create([
            'name' => 'Pastor Kenneth Osei',
            'title' => 'Senior Pastor',
            'organization' => 'CityLife Church',
            'bio' => 'Pastor Kenneth is the founding pastor of CityLife Church with over 20 years of ministry experience.',
            'email' => 'kenneth@citylife.org',
            'phone' => '+44 123 456 7890',
            'is_active' => true,
        ]);

        $speaker2 = BibleSchoolSpeaker::create([
            'name' => 'Rev. Sarah Thompson',
            'title' => 'Bible Teacher',
            'organization' => 'Kingdom Bible Institute',
            'bio' => 'Rev. Sarah is a renowned Bible teacher and author with a passion for teaching God\'s Word.',
            'email' => 'sarah@kbi.org',
            'is_active' => true,
        ]);

        $speaker3 = BibleSchoolSpeaker::create([
            'name' => 'Dr. David Mensah',
            'title' => 'Evangelist',
            'organization' => 'Global Outreach Ministry',
            'bio' => 'Dr. David has been in ministry for 15 years, preaching the gospel across Africa and Europe.',
            'email' => 'david@globaloutreach.org',
            'is_active' => true,
        ]);

        // Create sample events
        $event2026 = BibleSchoolEvent::create([
            'title' => 'Bible School International 2026',
            'description' => 'Join us for an intensive week-long Bible study program featuring renowned speakers and comprehensive teaching sessions.',
            'year' => 2026,
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-20',
            'location' => 'Online',
            'is_active' => true,
        ]);

        // Attach speakers to 2026 event
        $event2026->speakers()->attach([
            $speaker1->id => ['order' => 1],
            $speaker2->id => ['order' => 2],
            $speaker3->id => ['order' => 3],
        ]);

        $event2025 = BibleSchoolEvent::create([
            'title' => 'Bible School International 2025',
            'description' => 'Last year\'s successful Bible School program with teachings on Christian living and spiritual growth.',
            'year' => 2025,
            'start_date' => '2025-01-10',
            'end_date' => '2025-01-15',
            'location' => 'Online',
            'is_active' => true,
        ]);

        // Attach speakers to 2025 event
        $event2025->speakers()->attach([
            $speaker1->id => ['order' => 1],
            $speaker2->id => ['order' => 2],
        ]);

        // Create sample videos for 2026 event
        $videos2026 = [
            [
                'title' => 'Opening Session - Welcome & Introduction',
                'description' => 'Pastor welcomes students and introduces the Bible School program for 2026.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Sample URL
                'duration' => 3600, // 1 hour
                'order' => 1,
            ],
            [
                'title' => 'Session 2 - The Gospel of Grace',
                'description' => 'Deep dive into the gospel message and understanding God\'s grace.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 5400, // 1.5 hours
                'order' => 2,
            ],
            [
                'title' => 'Session 3 - The Power of Prayer',
                'description' => 'Learning to develop a powerful prayer life and intimacy with God.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 4500, // 1.25 hours
                'order' => 3,
            ],
            [
                'title' => 'Session 4 - Walking in the Spirit',
                'description' => 'Understanding how to walk daily in the power of the Holy Spirit.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 4200, // 1.17 hours
                'order' => 4,
            ],
            [
                'title' => 'Closing Session - Going Forward',
                'description' => 'Final encouragement and practical steps for continuing your spiritual journey.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 3000, // 50 minutes
                'order' => 5,
            ],
        ];

        foreach ($videos2026 as $videoData) {
            BibleSchoolVideo::create(array_merge($videoData, [
                'bible_school_event_id' => $event2026->id,
                'is_active' => true,
            ]));
        }

        // Create sample audios for 2026 event
        $audios2026 = [
            [
                'title' => 'Morning Devotional - Day 1',
                'description' => 'Start your day with worship and meditation on God\'s word.',
                'audio_url' => 'https://example.com/audio/devotional-day1.mp3',
                'duration' => 1800, // 30 minutes
                'order' => 1,
            ],
            [
                'title' => 'Morning Devotional - Day 2',
                'description' => 'Continue your journey with today\'s devotional message.',
                'audio_url' => 'https://example.com/audio/devotional-day2.mp3',
                'duration' => 1800,
                'order' => 2,
            ],
            [
                'title' => 'Morning Devotional - Day 3',
                'description' => 'Midweek encouragement and spiritual nourishment.',
                'audio_url' => 'https://example.com/audio/devotional-day3.mp3',
                'duration' => 1800,
                'order' => 3,
            ],
            [
                'title' => 'Worship & Praise Audio',
                'description' => 'Collection of worship songs from the event.',
                'audio_url' => 'https://example.com/audio/worship.mp3',
                'duration' => 2700, // 45 minutes
                'order' => 4,
            ],
        ];

        foreach ($audios2026 as $audioData) {
            BibleSchoolAudio::create(array_merge($audioData, [
                'bible_school_event_id' => $event2026->id,
                'is_active' => true,
            ]));
        }

        // Create sample videos for 2025 event (fewer resources)
        $videos2025 = [
            [
                'title' => '2025 Opening Session',
                'description' => 'Welcome to Bible School 2025',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 3600,
                'order' => 1,
            ],
            [
                'title' => '2025 Teaching on Faith',
                'description' => 'Understanding and growing your faith',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 4200,
                'order' => 2,
            ],
        ];

        foreach ($videos2025 as $videoData) {
            BibleSchoolVideo::create(array_merge($videoData, [
                'bible_school_event_id' => $event2025->id,
                'is_active' => true,
            ]));
        }

        // Create sample access codes
        $students = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com'],
            ['name' => 'Jane Doe', 'email' => 'jane.doe@example.com'],
            ['name' => 'Michael Johnson', 'email' => 'michael.j@example.com'],
            ['name' => 'Sarah Williams', 'email' => 'sarah.w@example.com'],
            ['name' => 'David Brown', 'email' => 'david.brown@example.com'],
        ];

        foreach ($students as $student) {
            // Create code for 2026 event
            BibleSchoolAccessCode::create([
                'code' => BibleSchoolAccessCode::generateUniqueCode(),
                'student_name' => $student['name'],
                'student_email' => $student['email'],
                'bible_school_event_id' => $event2026->id,
                'is_active' => true,
                'expires_at' => now()->addMonths(3), // Valid for 3 months
            ]);

            // Create code for 2025 event (expired example)
            BibleSchoolAccessCode::create([
                'code' => BibleSchoolAccessCode::generateUniqueCode(),
                'student_name' => $student['name'],
                'student_email' => $student['email'],
                'bible_school_event_id' => $event2025->id,
                'is_active' => true,
                'expires_at' => now()->subMonths(1), // Expired 1 month ago
                'usage_count' => rand(1, 10),
                'last_used_at' => now()->subWeeks(rand(4, 8)),
            ]);
        }

        // Create a demo access code that's easy to remember for testing
        BibleSchoolAccessCode::create([
            'code' => 'DEMO2026',
            'student_name' => 'Demo Student',
            'student_email' => 'demo@example.com',
            'bible_school_event_id' => $event2026->id,
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $this->command->info('Bible School sample data created successfully!');
        $this->command->info('Demo access code: DEMO2026 (for testing)');
    }
}
