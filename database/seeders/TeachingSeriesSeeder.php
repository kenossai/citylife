<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeachingSeries;
use Carbon\Carbon;

class TeachingSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachingSeries = [
            [
                'title' => 'Walking in Faith',
                'slug' => 'walking-in-faith',
                'summary' => 'Discover how to live a life of faith, trusting God in every circumstance and walking boldly in His promises.',
                'description' => '<p>This powerful series explores what it means to walk by faith and not by sight. Through biblical examples and practical applications, we learn how to trust God completely and live with unwavering faith in His goodness and sovereignty.</p><p>Join us as we examine the lives of faith heroes like Abraham, Moses, and David, and discover how their examples can transform our own walk with God.</p>',
                'pastor' => 'Pastor John Smith',
                'category' => 'Sermons',
                'tags' => ['faith', 'trust', 'biblical heroes', 'spiritual growth'],
                'series_date' => Carbon::now()->subDays(7),
                'duration_minutes' => 45,
                'scripture_references' => 'Hebrews 11:1-40, Romans 1:17, 2 Corinthians 5:7',
                'video_url' => 'https://youtube.com/watch?v=sample1',
                'audio_url' => 'https://soundcloud.com/sample-audio-1',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
                'views_count' => 245,
            ],
            [
                'title' => 'The Heart of Worship',
                'slug' => 'the-heart-of-worship',
                'summary' => 'Explore the true meaning of worship and how to cultivate a heart that honors God in all aspects of life.',
                'description' => '<p>Worship is more than singing songs on Sunday morning. This series dives deep into what it means to worship God with our entire lives, understanding that true worship flows from a heart transformed by grace.</p><p>We will explore both corporate and personal worship, examining how to make worship a lifestyle rather than just an event.</p>',
                'pastor' => 'Pastor Mary Johnson',
                'category' => 'Worship',
                'tags' => ['worship', 'praise', 'lifestyle', 'devotion'],
                'series_date' => Carbon::now()->subDays(14),
                'duration_minutes' => 38,
                'scripture_references' => 'John 4:23-24, Psalm 95:6-7, Romans 12:1',
                'video_url' => 'https://youtube.com/watch?v=sample2',
                'audio_url' => 'https://soundcloud.com/sample-audio-2',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
                'views_count' => 189,
            ],
            [
                'title' => 'Families That Follow Christ',
                'slug' => 'families-that-follow-christ',
                'summary' => 'Biblical principles for building strong, Christ-centered families that honor God and impact the world.',
                'description' => '<p>God designed the family to be a reflection of His love and character. This series provides practical wisdom for parents, children, and all family members on how to create homes filled with faith, love, and purpose.</p><p>From communication and conflict resolution to raising children in the faith, this series covers essential topics for every family seeking to follow Christ together.</p>',
                'pastor' => 'Pastor David Wilson',
                'category' => 'Family',
                'tags' => ['family', 'parenting', 'marriage', 'children', 'relationships'],
                'series_date' => Carbon::now()->subDays(21),
                'duration_minutes' => 42,
                'scripture_references' => 'Ephesians 5:22-6:4, Deuteronomy 6:4-9, Proverbs 22:6',
                'video_url' => 'https://youtube.com/watch?v=sample3',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 3,
                'views_count' => 156,
            ],
            [
                'title' => 'Power of Prayer',
                'slug' => 'power-of-prayer',
                'summary' => 'Unlock the transformative power of prayer and learn to communicate effectively with our Heavenly Father.',
                'description' => '<p>Prayer is our direct line to God, yet many believers struggle with maintaining a consistent and effective prayer life. This series teaches practical approaches to prayer, exploring different types of prayer and how to align our hearts with God\'s will.</p><p>Discover how prayer can transform not only our circumstances but also our hearts, drawing us closer to God and increasing our faith.</p>',
                'pastor' => 'Pastor John Smith',
                'category' => 'Prayer',
                'tags' => ['prayer', 'communication', 'spiritual disciplines', 'intimacy with God'],
                'series_date' => Carbon::now()->subDays(28),
                'duration_minutes' => 40,
                'scripture_references' => 'Matthew 6:9-13, Luke 11:1-13, James 5:16',
                'video_url' => 'https://youtube.com/watch?v=sample4',
                'audio_url' => 'https://soundcloud.com/sample-audio-4',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 4,
                'views_count' => 203,
            ],
            [
                'title' => 'Youth Rising: Living for Jesus',
                'slug' => 'youth-rising-living-for-jesus',
                'summary' => 'Empowering young people to live boldly for Christ in today\'s world and make a lasting impact.',
                'description' => '<p>Young people today face unique challenges and opportunities. This dynamic series speaks directly to youth, addressing relevant issues while providing biblical foundations for living a life that honors God.</p><p>From peer pressure and identity to purpose and calling, this series equips young believers with the tools they need to rise up and live boldly for Jesus.</p>',
                'pastor' => 'Pastor Mary Johnson',
                'category' => 'Youth',
                'tags' => ['youth', 'identity', 'purpose', 'bold living', 'discipleship'],
                'series_date' => Carbon::now()->subDays(35),
                'duration_minutes' => 35,
                'scripture_references' => '1 Timothy 4:12, Jeremiah 1:7, Ecclesiastes 12:1',
                'video_url' => 'https://youtube.com/watch?v=sample5',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 5,
                'views_count' => 178,
            ],
            [
                'title' => 'Foundations of Faith',
                'slug' => 'foundations-of-faith',
                'summary' => 'Essential biblical truths every believer should know to build a strong foundation for their faith journey.',
                'description' => '<p>Every strong building needs a solid foundation. This foundational series covers the core truths of Christianity, perfect for new believers or anyone wanting to strengthen their understanding of the faith.</p><p>We explore topics such as salvation, the Trinity, the authority of Scripture, and the importance of the church in the life of every believer.</p>',
                'pastor' => 'Pastor David Wilson',
                'category' => 'Bible Study',
                'tags' => ['foundations', 'basics', 'theology', 'new believers', 'discipleship'],
                'series_date' => Carbon::now()->subDays(42),
                'duration_minutes' => 50,
                'scripture_references' => '1 Corinthians 3:11, Ephesians 2:19-20, 2 Timothy 3:16-17',
                'audio_url' => 'https://soundcloud.com/sample-audio-6',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 6,
                'views_count' => 134,
            ],
        ];

        foreach ($teachingSeries as $series) {
            TeachingSeries::create($series);
        }
    }
}
