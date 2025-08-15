<?php

namespace Database\Seeders;

use App\Models\Ministry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinistrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ministries = [
            [
                'name' => 'City Life Kids',
                'slug' => 'city-life-kids',
                'ministry_type' => 'kids',
                'description' => 'A vibrant ministry dedicated to nurturing children in their faith journey through fun, engaging activities and biblical teachings.',
                'content' => '<p>Our City Life Kids ministry is designed to create a safe, fun, and engaging environment where children can learn about God\'s love and develop a strong foundation of faith.</p><p>We offer age-appropriate programming for children from nursery through elementary school, with dedicated volunteers who are passionate about investing in the next generation.</p><h4>What We Offer:</h4><ul><li>Sunday School classes for all ages</li><li>Vacation Bible School</li><li>Children\'s church during main service</li><li>Special events and activities</li><li>Character building programs</li></ul>',
                'leader' => 'Sarah Johnson',
                'contact_email' => 'kids@citylifecc.com',
                'meeting_time' => 'Sundays 9:00 AM & 11:00 AM',
                'meeting_location' => 'Children\'s Wing',
                'how_to_join' => 'Contact our Children\'s Ministry team or visit us on Sunday morning.',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Youth Ministry',
                'slug' => 'youth-ministry',
                'ministry_type' => 'youth',
                'description' => 'Empowering teenagers to develop authentic relationships with God and each other through relevant teaching and community.',
                'content' => '<p>Our Youth Ministry is committed to helping teenagers navigate the challenges of adolescence while building a strong relationship with Jesus Christ.</p><p>We create an environment where teens can ask questions, explore their faith, and build lasting friendships with their peers.</p><h4>Programs Include:</h4><ul><li>Weekly youth group meetings</li><li>Bible study groups</li><li>Youth retreats and camps</li><li>Service projects</li><li>Mentorship programs</li></ul>',
                'leader' => 'Michael Chen',
                'contact_email' => 'youth@citylifecc.com',
                'meeting_time' => 'Wednesdays 7:00 PM',
                'meeting_location' => 'Youth Center',
                'how_to_join' => 'Come to Wednesday night youth group or contact our youth pastor.',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Young Adults Ministry',
                'slug' => 'young-adults-ministry',
                'ministry_type' => 'other',
                'description' => 'Building community and faith among young adults navigating career, relationships, and life transitions.',
                'content' => '<p>Our Young Adults Ministry focuses on creating meaningful connections and spiritual growth for adults in their 20s and 30s.</p><p>We understand the unique challenges of this life stage and provide support, community, and opportunities for spiritual development.</p><h4>Activities Include:</h4><ul><li>Weekly gatherings</li><li>Small group Bible studies</li><li>Social events and outings</li><li>Career and relationship workshops</li><li>Community service projects</li></ul>',
                'leader' => 'David Rodriguez',
                'contact_email' => 'youngadults@citylifecc.com',
                'meeting_time' => 'Fridays 7:30 PM',
                'meeting_location' => 'Fellowship Hall',
                'how_to_join' => 'Join us Friday evenings or connect with us through social media.',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Women\'s Ministry',
                'slug' => 'womens-ministry',
                'ministry_type' => 'womens',
                'description' => 'Encouraging women to grow in their relationship with God while building supportive friendships with other women.',
                'content' => '<p>Our Women\'s Ministry provides opportunities for women of all ages to connect, grow, and serve together.</p><p>We offer various programs designed to meet women where they are in their spiritual journey and life circumstances.</p><h4>Ministry Offerings:</h4><ul><li>Women\'s Bible studies</li><li>Prayer groups</li><li>Retreat weekends</li><li>Mentorship programs</li><li>Community outreach projects</li></ul>',
                'leader' => 'Rebecca Thompson',
                'contact_email' => 'women@citylifecc.com',
                'meeting_time' => 'Tuesdays 7:00 PM',
                'meeting_location' => 'Women\'s Ministry Room',
                'how_to_join' => 'Contact our Women\'s Ministry leader or attend a Tuesday evening gathering.',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Men\'s Ministry',
                'slug' => 'mens-ministry',
                'ministry_type' => 'mens',
                'description' => 'Equipping men to be godly leaders in their homes, workplaces, and communities through fellowship and discipleship.',
                'content' => '<p>Our Men\'s Ministry is dedicated to helping men grow in their faith and develop into the leaders God has called them to be.</p><p>We focus on building strong Christian character, developing leadership skills, and creating accountability relationships.</p><h4>Programs Include:</h4><ul><li>Men\'s Bible study groups</li><li>Breakfast meetings</li><li>Service projects</li><li>Men\'s retreats</li><li>Mentorship and accountability groups</li></ul>',
                'leader' => 'James Wilson',
                'contact_email' => 'men@citylifecc.com',
                'meeting_time' => 'Saturdays 8:00 AM',
                'meeting_location' => 'Men\'s Ministry Room',
                'how_to_join' => 'Join us Saturday mornings for breakfast and fellowship.',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Worship Ministry',
                'slug' => 'worship-ministry',
                'ministry_type' => 'worship',
                'description' => 'Leading the congregation in heartfelt worship through music, creating an atmosphere where people can connect with God.',
                'content' => '<p>Our Worship Ministry is passionate about creating an environment where people can experience God\'s presence through music and worship.</p><p>We welcome musicians, singers, and anyone with a heart for worship to join our team.</p><h4>Opportunities Include:</h4><ul><li>Sunday worship team</li><li>Choir participation</li><li>Special music events</li><li>Technical support roles</li><li>Worship training and development</li></ul>',
                'leader' => 'Emily Davis',
                'contact_email' => 'worship@citylifecc.com',
                'meeting_time' => 'Thursdays 7:00 PM (Practice)',
                'meeting_location' => 'Sanctuary',
                'how_to_join' => 'Contact our worship leader or attend Thursday practice sessions.',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Prayer Ministry',
                'slug' => 'prayer-ministry',
                'ministry_type' => 'prayer',
                'description' => 'Dedicated to intercession and creating a culture of prayer throughout our church community.',
                'content' => '<p>Our Prayer Ministry is the spiritual backbone of our church, committed to interceding for our congregation, community, and world.</p><p>We believe in the power of prayer and invite everyone to join us in seeking God\'s will and blessing.</p><h4>Prayer Opportunities:</h4><ul><li>Weekly prayer meetings</li><li>Prayer chains for urgent needs</li><li>Intercessory prayer teams</li><li>Prayer walks in the community</li><li>Teaching on prayer and spiritual warfare</li></ul>',
                'leader' => 'Pastor Mary Johnson',
                'contact_email' => 'prayer@citylifecc.com',
                'meeting_time' => 'Wednesdays 6:00 AM & 7:00 PM',
                'meeting_location' => 'Prayer Chapel',
                'how_to_join' => 'Join us for Wednesday morning or evening prayer meetings.',
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($ministries as $ministry) {
            Ministry::create($ministry);
        }
    }
}
