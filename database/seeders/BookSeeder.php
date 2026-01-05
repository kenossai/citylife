<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\TeamMember;
use Carbon\Carbon;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some team members to be authors
        $jimMaster = TeamMember::where('first_name', 'Jim')->where('last_name', 'Master')->first();
        $anthonyNicholson = TeamMember::where('first_name', 'Anthony')->where('last_name', 'Nicholson')->first();

        if ($jimMaster) {
            // Sample books by Pastor Jim Master
            Book::create([
                'team_member_id' => $jimMaster->id,
                'title' => 'Walking in Faith',
                'subtitle' => 'A Journey Through Daily Devotion',
                'isbn' => '978-1-234567-89-0',
                'isbn13' => '978-1-234567-89-0',
                'description' => '<p>Walking in Faith is a comprehensive guide to deepening your spiritual journey through daily devotion and prayer. This book offers practical insights and biblical wisdom for Christians seeking to strengthen their relationship with God.</p><p>Drawing from years of pastoral experience, Pastor Jim Master provides readers with actionable steps to develop a consistent prayer life, understand Scripture more deeply, and apply biblical principles to everyday challenges.</p>',
                'short_description' => 'A comprehensive guide to deepening your spiritual journey through daily devotion and prayer.',
                'publisher' => 'Faith Publishing House',
                'published_date' => Carbon::parse('2022-03-15'),
                'edition' => '1st Edition',
                'language' => 'English',
                'pages' => 256,
                'format' => 'paperback',
                'price' => 14.99,
                'currency' => 'GBP',
                'category' => 'Devotional',
                'tags' => ['Faith', 'Prayer', 'Devotional', 'Spiritual Growth'],
                'topics' => ['Daily Devotion', 'Prayer Life', 'Scripture Reading', 'Spiritual Disciplines'],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'slug' => 'walking-in-faith',
                'meta_title' => 'Walking in Faith - Daily Devotional Guide by Pastor Jim Master',
                'meta_description' => 'Deepen your spiritual journey with this comprehensive guide to daily devotion and prayer by Pastor Jim Master.',
                'meta_keywords' => ['faith', 'devotional', 'prayer', 'christian living', 'spiritual growth'],
            ]);

            Book::create([
                'team_member_id' => $jimMaster->id,
                'title' => 'Leadership in the Church',
                'subtitle' => 'Biblical Principles for Modern Ministry',
                'isbn' => '978-1-234567-90-6',
                'isbn13' => '978-1-234567-90-6',
                'description' => '<p>Leadership in the Church explores timeless biblical principles for effective church leadership in the modern era. This book is essential reading for pastors, church leaders, and anyone involved in ministry.</p><p>Through practical examples and biblical teaching, Pastor Jim Master guides readers through the challenges and opportunities of church leadership, offering wisdom gained from decades of ministry experience.</p>',
                'short_description' => 'Explore timeless biblical principles for effective church leadership in the modern era.',
                'publisher' => 'Ministry Press',
                'published_date' => Carbon::parse('2020-09-01'),
                'edition' => '2nd Edition',
                'language' => 'English',
                'pages' => 320,
                'format' => 'hardcover',
                'price' => 19.99,
                'currency' => 'GBP',
                'category' => 'Leadership',
                'tags' => ['Leadership', 'Church', 'Ministry', 'Pastoral Care'],
                'topics' => ['Church Leadership', 'Ministry Development', 'Team Building', 'Vision Casting'],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'slug' => 'leadership-in-the-church',
                'meta_title' => 'Leadership in the Church - Biblical Principles for Modern Ministry',
                'meta_description' => 'Essential reading for church leaders exploring biblical principles for effective ministry in the modern era.',
                'meta_keywords' => ['church leadership', 'ministry', 'pastoral', 'biblical leadership'],
            ]);

            Book::create([
                'team_member_id' => $jimMaster->id,
                'title' => 'The Power of Prayer',
                'subtitle' => 'Unlocking God\'s Promises',
                'isbn' => '978-1-234567-91-3',
                'isbn13' => '978-1-234567-91-3',
                'description' => '<p>Discover the transformative power of prayer in your life. This book delves into biblical teaching on prayer, showing how believers can access God\'s promises through faithful, persistent prayer.</p>',
                'short_description' => 'Discover the transformative power of prayer and unlock God\'s promises in your life.',
                'publisher' => 'Faith Publishing House',
                'published_date' => Carbon::parse('2023-06-20'),
                'edition' => '1st Edition',
                'language' => 'English',
                'pages' => 192,
                'format' => 'ebook',
                'price' => 9.99,
                'currency' => 'GBP',
                'purchase_link' => 'https://example.com/power-of-prayer',
                'amazon_link' => 'https://amazon.co.uk/power-of-prayer',
                'category' => 'Prayer',
                'tags' => ['Prayer', 'Faith', 'Spiritual Warfare', 'God\'s Promises'],
                'topics' => ['Prayer', 'Intercession', 'Spiritual Breakthrough', 'Faith'],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'slug' => 'the-power-of-prayer',
                'meta_title' => 'The Power of Prayer - Unlocking God\'s Promises',
                'meta_description' => 'Learn how to unlock God\'s promises through the transformative power of prayer.',
                'meta_keywords' => ['prayer', 'faith', 'spiritual warfare', 'christian prayer'],
            ]);
        }

        if ($anthonyNicholson) {
            // Sample book by Pastor Anthony Nicholson
            Book::create([
                'team_member_id' => $anthonyNicholson->id,
                'title' => 'Discipleship Foundations',
                'subtitle' => 'Building Strong Christians',
                'isbn' => '978-1-234567-92-0',
                'isbn13' => '978-1-234567-92-0',
                'description' => '<p>Discipleship Foundations provides a systematic approach to building strong, mature Christians. This comprehensive guide covers essential topics every believer needs to grow in their faith.</p><p>Pastor Anthony Nicholson draws from years of experience in discipleship ministry to provide practical teaching and actionable steps for spiritual growth.</p>',
                'short_description' => 'A systematic approach to building strong, mature Christians through effective discipleship.',
                'publisher' => 'Disciple Press',
                'published_date' => Carbon::parse('2021-11-10'),
                'edition' => '1st Edition',
                'language' => 'English',
                'pages' => 288,
                'format' => 'paperback',
                'price' => 16.99,
                'currency' => 'GBP',
                'category' => 'Christian Living',
                'tags' => ['Discipleship', 'Spiritual Growth', 'Christian Education', 'Mentorship'],
                'topics' => ['Discipleship', 'Spiritual Maturity', 'Biblical Teaching', 'Mentoring'],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'slug' => 'discipleship-foundations',
                'meta_title' => 'Discipleship Foundations - Building Strong Christians',
                'meta_description' => 'A comprehensive guide to building strong, mature Christians through systematic discipleship.',
                'meta_keywords' => ['discipleship', 'spiritual growth', 'christian education', 'mentorship'],
            ]);
        }

        $this->command->info('Sample books seeded successfully!');
    }
}
