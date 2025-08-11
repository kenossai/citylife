<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Sunday Morning Service',
            'slug' => 'sunday-morning-service',
            'description' => 'Join us for our weekly Sunday morning worship service filled with praise, worship, and biblical teaching.',
            'content' => '<p>Every Sunday morning, we gather as a church family to worship God together. Our service includes contemporary worship music, prayer, and expository preaching from God\'s Word.</p><p>Come as you are and experience the love and fellowship of our church community. We have programs for all ages including Sunday School for children and adults.</p>',
            'start_date' => Carbon::now()->next(Carbon::SUNDAY)->setTime(10, 0),
            'end_date' => Carbon::now()->next(Carbon::SUNDAY)->setTime(11, 30),
            'location' => 'Main Sanctuary',
            'event_anchor' => 'Pastor Johnson',
            'guest_speaker' => null,
            'requires_registration' => false,
            'featured_image' => 'assets/images/events/event-2-1.jpg',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Wednesday Bible Study',
            'slug' => 'wednesday-bible-study',
            'description' => 'Dive deeper into God\'s Word through our weekly Bible study sessions.',
            'content' => '<p>Join us every Wednesday evening for an in-depth study of the Bible. Our Bible study is designed for believers of all maturity levels who want to grow in their understanding of Scripture.</p><p>We provide study materials and encourage discussion and questions. Light refreshments are provided.</p>',
            'start_date' => Carbon::now()->next(Carbon::WEDNESDAY)->setTime(19, 0),
            'end_date' => Carbon::now()->next(Carbon::WEDNESDAY)->setTime(20, 30),
            'location' => 'Fellowship Hall',
            'event_anchor' => 'Elder Smith',
            'guest_speaker' => null,
            'requires_registration' => false,
            'featured_image' => 'assets/images/events/event-2-2.jpg',
            'is_featured' => false,
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Youth Group Meeting',
            'slug' => 'youth-group-meeting',
            'description' => 'A dynamic gathering for teenagers to connect, learn, and grow in their faith.',
            'content' => '<p>Our youth group meets every Friday evening for a time of fellowship, games, worship, and Biblical teaching specifically designed for teenagers.</p><p>We focus on relevant topics that help young people navigate life with a Biblical worldview. Pizza and snacks are always provided!</p>',
            'start_date' => Carbon::now()->next(Carbon::FRIDAY)->setTime(18, 30),
            'end_date' => Carbon::now()->next(Carbon::FRIDAY)->setTime(21, 0),
            'location' => 'Youth Center',
            'event_anchor' => 'Pastor Mike',
            'guest_speaker' => null,
            'requires_registration' => false,
            'featured_image' => 'assets/images/events/event-2-3.jpg',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Community Outreach Day',
            'slug' => 'community-outreach-day',
            'description' => 'Join us as we serve our local community through various outreach activities.',
            'content' => '<p>Once a month, we organize community outreach activities to serve our neighbors and show God\'s love in practical ways.</p><p>Activities include food distribution, neighborhood clean-up, visiting nursing homes, and other service projects. It\'s a great way to live out our faith and make a positive impact in our community.</p>',
            'start_date' => Carbon::now()->addWeeks(2)->next(Carbon::SATURDAY)->setTime(9, 0),
            'end_date' => Carbon::now()->addWeeks(2)->next(Carbon::SATURDAY)->setTime(15, 0),
            'location' => 'Various Community Locations',
            'event_anchor' => 'Ministry Leader Sarah',
            'guest_speaker' => null,
            'requires_registration' => true,
            'registration_details' => 'Please register by calling the church office at (555) 123-4567 or sign up at the front desk. We need to know how many volunteers to expect for proper planning.',
            'max_attendees' => 50,
            'featured_image' => 'assets/images/events/event-2-4.jpg',
            'is_featured' => false,
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Annual Church Picnic',
            'slug' => 'annual-church-picnic',
            'description' => 'A fun-filled day of fellowship, food, and family activities for all ages.',
            'content' => '<p>Join us for our annual church picnic featuring delicious food, games for all ages, and great fellowship. This is one of our favorite events of the year where the whole church family comes together for fun and community.</p><p>We\'ll have bounce houses for kids, volleyball and other games, live music, and plenty of great food. Bring your family and friends for a memorable day!</p>',
            'start_date' => Carbon::now()->addMonths(1)->next(Carbon::SATURDAY)->setTime(11, 0),
            'end_date' => Carbon::now()->addMonths(1)->next(Carbon::SATURDAY)->setTime(16, 0),
            'location' => 'Church Grounds & Fellowship Hall',
            'event_anchor' => 'Event Committee',
            'guest_speaker' => null,
            'requires_registration' => true,
            'registration_details' => 'Please register your family so we can plan for food and activities. Children under 12 eat free!',
            'max_attendees' => 200,
            'featured_image' => 'assets/images/events/event-2-5.jpg',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Christmas Concert',
            'slug' => 'christmas-concert',
            'description' => 'Celebrate the birth of Jesus with beautiful music and inspiring performances.',
            'content' => '<p>Our annual Christmas concert features our church choir, orchestra, and special guest performers celebrating the birth of our Savior Jesus Christ.</p><p>This is a wonderful opportunity to invite friends and family to hear the Christmas story told through beautiful music and Scripture readings. Admission is free and refreshments will be served afterward.</p>',
            'start_date' => Carbon::create(Carbon::now()->year, 12, 15)->setTime(19, 0),
            'end_date' => Carbon::create(Carbon::now()->year, 12, 15)->setTime(21, 0),
            'location' => 'Main Sanctuary',
            'event_anchor' => 'Music Director David',
            'guest_speaker' => 'Renowned Gospel Singer Maria Rodriguez',
            'requires_registration' => false,
            'featured_image' => 'assets/images/events/event-2-6.jpg',
            'is_featured' => true,
            'is_published' => true,
        ]);
    }
}
