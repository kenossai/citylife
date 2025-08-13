<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CityLifeTalkTime;
use Carbon\Carbon;

class CityLifeTalkTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $episodes = [
            [
                'title' => 'Faith in the Modern World',
                'slug' => 'faith-in-the-modern-world',
                'description' => 'Join us as we explore how to maintain and strengthen our faith in today\'s rapidly changing world. We discuss practical ways to live out our Christian values in contemporary society.',
                'video_url' => 'https://youtube.com/watch?v=example1',
                'host' => 'Pastor John Smith',
                'guest' => 'Dr. Sarah Johnson',
                'episode_date' => Carbon::now()->subDays(7),
                'duration_minutes' => 45,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Building Strong Relationships',
                'slug' => 'building-strong-relationships',
                'description' => 'Discover biblical principles for building and maintaining strong, healthy relationships in marriage, family, and friendship.',
                'video_url' => 'https://youtube.com/watch?v=example2',
                'host' => 'Pastor Mary Wilson',
                'guest' => 'Counselor Mike Brown',
                'episode_date' => Carbon::now()->subDays(14),
                'duration_minutes' => 52,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Finding Purpose in Your Calling',
                'slug' => 'finding-purpose-in-your-calling',
                'description' => 'How do we discover God\'s calling on our lives? This episode dives deep into understanding your unique purpose and how to walk in it confidently.',
                'video_url' => 'https://youtube.com/watch?v=example3',
                'host' => 'Pastor John Smith',
                'guest' => 'Life Coach Jennifer Davis',
                'episode_date' => Carbon::now()->subDays(21),
                'duration_minutes' => 38,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Youth Ministry and Community Impact',
                'slug' => 'youth-ministry-and-community-impact',
                'description' => 'A conversation about engaging young people in meaningful ministry and creating positive change in our communities.',
                'video_url' => 'https://youtube.com/watch?v=example4',
                'host' => 'Pastor David Lee',
                'guest' => 'Youth Pastor Amanda Clark',
                'episode_date' => Carbon::now()->subDays(28),
                'duration_minutes' => 41,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Prayer: The Foundation of Faith',
                'slug' => 'prayer-the-foundation-of-faith',
                'description' => 'Exploring different types of prayer, developing a consistent prayer life, and understanding how prayer transforms us.',
                'video_url' => 'https://youtube.com/watch?v=example5',
                'host' => 'Pastor Mary Wilson',
                'guest' => 'Prayer Ministry Leader Ruth Garcia',
                'episode_date' => Carbon::now()->subDays(35),
                'duration_minutes' => 47,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Overcoming Life\'s Challenges',
                'slug' => 'overcoming-lifes-challenges',
                'description' => 'Real talk about facing difficulties, setbacks, and trials with faith and resilience. Hear testimonies of breakthrough and hope.',
                'video_url' => 'https://youtube.com/watch?v=example6',
                'host' => 'Pastor John Smith',
                'guest' => 'Testimony Speaker Lisa Martin',
                'episode_date' => Carbon::now()->subDays(42),
                'duration_minutes' => 55,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'The Heart of Worship',
                'slug' => 'the-heart-of-worship',
                'description' => 'Understanding true worship that goes beyond Sunday service - how to cultivate a lifestyle of worship in everyday life.',
                'video_url' => 'https://youtube.com/watch?v=example7',
                'host' => 'Pastor David Lee',
                'guest' => 'Worship Leader Carlos Rodriguez',
                'episode_date' => Carbon::now()->subDays(49),
                'duration_minutes' => 43,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'Serving Others with Excellence',
                'slug' => 'serving-others-with-excellence',
                'description' => 'How to serve in ministry and the community with a heart of excellence, humility, and joy.',
                'video_url' => 'https://youtube.com/watch?v=example8',
                'host' => 'Pastor Mary Wilson',
                'guest' => 'Volunteer Coordinator Tom Anderson',
                'episode_date' => Carbon::now()->subDays(56),
                'duration_minutes' => 39,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($episodes as $episode) {
            CityLifeTalkTime::create($episode);
        }

        $this->command->info('CityLife TalkTime episodes seeded successfully!');
    }
}
