<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamMember;
use Illuminate\Support\Str;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Pastoral Team Members
        TeamMember::create([
            'first_name' => 'Jim',
            'last_name' => 'Master',
            'title' => 'Pastor',
            'position' => 'Senior Pastor',
            'team_type' => 'pastoral',
            'email' => 'jim@citylifecc.com',
            'bio' => 'Pastor Jim Master serves as the Senior Pastor of City Life Christian Centre. With years of ministry experience, he leads the congregation with wisdom and passion for God\'s word.',
            'short_description' => 'Senior Pastor with a heart for evangelism and church growth.',
            'ministry_focus' => 'Church leadership, preaching, and evangelism',
            'responsibilities' => ['Overall church leadership', 'Preaching', 'Vision casting', 'Pastoral care'],
            'ministry_areas' => ['Leadership', 'Evangelism', 'Teaching'],
            'joined_church' => 2010,
            'started_ministry' => 2005,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 1,
            'slug' => 'jim-master',
        ]);

        TeamMember::create([
            'first_name' => 'Anthony',
            'last_name' => 'Nicholson',
            'title' => 'Pastor',
            'position' => 'Assistant Pastor',
            'team_type' => 'pastoral',
            'email' => 'anthony@citylifecc.com',
            'bio' => 'Pastor Anthony Nicholson serves as Assistant Pastor, supporting the Senior Pastor in various ministry functions and church operations.',
            'short_description' => 'Assistant Pastor focused on discipleship and community outreach.',
            'ministry_focus' => 'Discipleship, community outreach, and pastoral support',
            'responsibilities' => ['Discipleship programs', 'Community outreach', 'Pastoral assistance', 'Youth mentoring'],
            'ministry_areas' => ['Discipleship', 'Outreach', 'Youth Ministry'],
            'joined_church' => 2012,
            'started_ministry' => 2008,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 2,
            'slug' => 'anthony-nicholson',
        ]);

        TeamMember::create([
            'first_name' => 'Terence',
            'last_name' => 'Williams',
            'title' => 'Pastor',
            'position' => 'Assistant Pastor',
            'team_type' => 'pastoral',
            'email' => 'terence@citylifecc.com',
            'bio' => 'Pastor Terence Williams serves as Assistant Pastor, bringing passion for worship and spiritual development to the ministry team.',
            'short_description' => 'Assistant Pastor with expertise in worship and spiritual growth.',
            'ministry_focus' => 'Worship ministry, spiritual development, and prayer',
            'responsibilities' => ['Worship coordination', 'Prayer ministry', 'Spiritual counseling', 'Music ministry'],
            'ministry_areas' => ['Worship', 'Prayer', 'Music Ministry'],
            'joined_church' => 2013,
            'started_ministry' => 2009,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 3,
            'slug' => 'terence-williams',
        ]);

        // Leadership Team Members
        TeamMember::create([
            'first_name' => 'Vivienne',
            'last_name' => 'Williams',
            'position' => 'Church Administrator',
            'team_type' => 'leadership',
            'email' => 'vivienne@citylifecc.com',
            'bio' => 'Vivienne Williams serves as Church Administrator, overseeing the daily operations and administrative functions of City Life Christian Centre.',
            'short_description' => 'Church Administrator ensuring smooth operations and excellent service.',
            'ministry_focus' => 'Church administration, operations management, and member services',
            'responsibilities' => ['Administrative oversight', 'Operations management', 'Member services', 'Event coordination'],
            'ministry_areas' => ['Administration', 'Operations', 'Member Care'],
            'joined_church' => 2011,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 1,
            'slug' => 'vivienne-williams',
        ]);

        TeamMember::create([
            'first_name' => 'Sofia',
            'last_name' => 'Margaret',
            'position' => 'Worship Leader',
            'team_type' => 'leadership',
            'email' => 'sofia@citylifecc.com',
            'bio' => 'Sofia Margaret leads the worship team at City Life Christian Centre, bringing passion and excellence to our worship services.',
            'short_description' => 'Worship Leader dedicated to creating meaningful worship experiences.',
            'ministry_focus' => 'Worship leadership, music ministry, and team development',
            'responsibilities' => ['Worship team leadership', 'Music coordination', 'Song selection', 'Team training'],
            'ministry_areas' => ['Worship', 'Music', 'Arts'],
            'joined_church' => 2014,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => false,
            'sort_order' => 2,
            'slug' => 'sofia-margaret',
        ]);

        TeamMember::create([
            'first_name' => 'James',
            'last_name' => 'Cutts',
            'position' => 'Youth Pastor',
            'team_type' => 'leadership',
            'email' => 'james@citylifecc.com',
            'bio' => 'James Cutts leads the youth ministry at City Life Christian Centre, passionate about developing the next generation of Christian leaders.',
            'short_description' => 'Youth Pastor committed to empowering young people in their faith journey.',
            'ministry_focus' => 'Youth ministry, leadership development, and mentorship',
            'responsibilities' => ['Youth program leadership', 'Mentorship', 'Event planning', 'Parent communication'],
            'ministry_areas' => ['Youth Ministry', 'Leadership Development', 'Education'],
            'joined_church' => 2015,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 3,
            'slug' => 'james-cutts',
        ]);

        TeamMember::create([
            'first_name' => 'James',
            'last_name' => 'Berry',
            'position' => 'Children\'s Ministry Director',
            'team_type' => 'leadership',
            'email' => 'jamesb@citylifecc.com',
            'bio' => 'James Berry directs the children\'s ministry at City Life Christian Centre, creating engaging and educational programs for our youngest members.',
            'short_description' => 'Children\'s Ministry Director focused on nurturing young hearts.',
            'ministry_focus' => 'Children\'s ministry, family support, and educational programs',
            'responsibilities' => ['Children\'s program development', 'Volunteer coordination', 'Family events', 'Safety protocols'],
            'ministry_areas' => ['Children\'s Ministry', 'Family Ministry', 'Education'],
            'joined_church' => 2016,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 4,
            'slug' => 'james-berry',
        ]);

        TeamMember::create([
            'first_name' => 'Abigail',
            'last_name' => 'Pinnock',
            'position' => 'Women\'s Ministry Leader',
            'team_type' => 'leadership',
            'email' => 'abigail@citylifecc.com',
            'bio' => 'Abigail Pinnock leads the women\'s ministry at City Life Christian Centre, fostering community and spiritual growth among women of all ages.',
            'short_description' => 'Women\'s Ministry Leader building strong community bonds.',
            'ministry_focus' => 'Women\'s ministry, community building, and spiritual mentorship',
            'responsibilities' => ['Women\'s program leadership', 'Community events', 'Mentorship programs', 'Prayer groups'],
            'ministry_areas' => ['Women\'s Ministry', 'Community Building', 'Mentorship'],
            'joined_church' => 2017,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 5,
            'slug' => 'abigail-pinnock',
        ]);

        TeamMember::create([
            'first_name' => 'Anoop',
            'last_name' => 'John',
            'position' => 'Community Outreach Coordinator',
            'team_type' => 'leadership',
            'email' => 'anoop@citylifecc.com',
            'bio' => 'Anoop John coordinates community outreach efforts for City Life Christian Centre, connecting the church with the broader community through various service programs.',
            'short_description' => 'Community Outreach Coordinator bridging church and community.',
            'ministry_focus' => 'Community outreach, social services, and partnership development',
            'responsibilities' => ['Outreach program coordination', 'Community partnerships', 'Service projects', 'Volunteer management'],
            'ministry_areas' => ['Community Outreach', 'Social Services', 'Missions'],
            'joined_church' => 2018,
            'is_active' => true,
            'is_featured' => true,
            'show_contact_info' => true,
            'sort_order' => 6,
            'slug' => 'anoop-john',
        ]);
    }
}
