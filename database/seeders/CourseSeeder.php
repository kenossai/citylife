<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bible Study Courses
        Course::create([
            'title' => 'Introduction to Christianity',
            'description' => 'A comprehensive introduction to the Christian faith, covering basic beliefs, the Gospel, and practical Christian living.',
            'content' => 'This course is designed for new believers and those exploring the Christian faith. We will cover the fundamentals of Christianity including salvation, the Trinity, the authority of Scripture, and how to live as a follower of Christ.',
            'instructor' => 'Pastor John Smith',
            'category' => 'Bible Study',
            'duration_weeks' => 8,
            'schedule' => 'Wednesdays 7:00-8:30 PM',
            'start_date' => '2025-09-10',
            'end_date' => '2025-10-29',
            'location' => 'Fellowship Hall',
            'requirements' => 'Open to all ages and backgrounds. No prior biblical knowledge required.',
            'what_you_learn' => 'Biblical foundations of faith, Gospel message, Prayer and Bible study methods, Christian community living',
            'course_objectives' => 'Understand core Christian beliefs, Develop personal relationship with God, Learn to read and study the Bible, Connect with the church community',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 6,
            'is_registration_open' => true,
            'sort_order' => 1,
        ]);

        Course::create([
            'title' => 'Old Testament Survey',
            'description' => 'Journey through the Old Testament from Genesis to Malachi, understanding God\'s plan of redemption.',
            'content' => 'This comprehensive study takes you through the major themes, characters, and events of the Old Testament. Learn how each book fits into God\'s overall plan for humanity.',
            'instructor' => 'Dr. Sarah Johnson',
            'category' => 'Bible Study',
            'duration_weeks' => 12,
            'schedule' => 'Sundays 9:00-10:00 AM',
            'start_date' => '2025-09-07',
            'end_date' => '2025-11-23',
            'location' => 'Room 201',
            'requirements' => 'Basic familiarity with the Bible helpful but not required.',
            'what_you_learn' => 'Old Testament chronology, Major themes and covenants, Key biblical characters, Historical and cultural context',
            'course_objectives' => 'Gain overview of Old Testament, Understand prophetic themes, See Christ in the Old Testament, Apply Old Testament lessons to modern life',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 9,
            'is_registration_open' => true,
            'sort_order' => 2,
        ]);

        Course::create([
            'title' => 'New Testament Survey',
            'description' => 'Explore the life of Christ, the early church, and the epistles in this comprehensive New Testament study.',
            'content' => 'Study the Gospels, Acts, and the epistles to understand the foundation of the Christian church and the teachings of Jesus and the apostles.',
            'instructor' => 'Rev. Michael Davis',
            'category' => 'Bible Study',
            'duration_weeks' => 10,
            'schedule' => 'Tuesdays 7:00-8:30 PM',
            'start_date' => '2025-10-07',
            'end_date' => '2025-12-09',
            'location' => 'Sanctuary',
            'requirements' => 'Completion of Introduction to Christianity recommended.',
            'what_you_learn' => 'Life and teachings of Jesus, Early church history, Pauline theology, Practical Christian living',
            'course_objectives' => 'Understand Gospel message, Learn apostolic teachings, Study early church growth, Apply New Testament principles',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 8,
            'is_registration_open' => true,
            'sort_order' => 3,
        ]);

        // Leadership & Ministry Courses
        Course::create([
            'title' => 'Leadership Development',
            'description' => 'Develop biblical leadership skills for ministry and life through practical training and mentorship.',
            'content' => 'This course focuses on developing leaders who can serve effectively in various ministry roles within the church and community.',
            'instructor' => 'Pastor David Wilson',
            'category' => 'Leadership',
            'duration_weeks' => 6,
            'schedule' => 'Saturdays 9:00-11:00 AM',
            'start_date' => '2025-09-14',
            'end_date' => '2025-10-19',
            'location' => 'Conference Room',
            'requirements' => 'Must be active church member for at least 1 year.',
            'what_you_learn' => 'Biblical leadership principles, Team building and communication, Conflict resolution, Ministry planning and execution',
            'course_objectives' => 'Develop leadership skills, Understand servant leadership, Learn to mentor others, Prepare for ministry roles',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 5,
            'is_registration_open' => true,
            'sort_order' => 4,
        ]);

        Course::create([
            'title' => 'Children\'s Ministry Training',
            'description' => 'Equip volunteers to effectively minister to children with age-appropriate teaching methods.',
            'content' => 'Learn how to create engaging, safe, and spiritually enriching experiences for children in various church settings.',
            'instructor' => 'Emma Thompson',
            'category' => 'Ministry Training',
            'duration_weeks' => 4,
            'schedule' => 'Thursdays 7:00-8:00 PM',
            'start_date' => '2025-09-12',
            'end_date' => '2025-10-03',
            'location' => 'Children\'s Wing',
            'requirements' => 'Background check required. Heart for children\'s ministry.',
            'what_you_learn' => 'Child development basics, Creative teaching methods, Safety protocols, Classroom management',
            'course_objectives' => 'Understand children\'s needs, Master teaching techniques, Ensure child safety, Build relationships with families',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 4,
            'is_registration_open' => true,
            'sort_order' => 5,
        ]);

        // Practical Christian Living
        Course::create([
            'title' => 'Marriage Enrichment',
            'description' => 'Strengthen your marriage with biblical principles and practical tools for healthy relationships.',
            'content' => 'This course is designed for married couples to deepen their relationship and build a Christ-centered marriage.',
            'instructor' => 'Pastor Mark & Lisa Brown',
            'category' => 'Family Life',
            'duration_weeks' => 5,
            'schedule' => 'Friday evenings 7:00-9:00 PM',
            'start_date' => '2025-10-04',
            'end_date' => '2025-11-01',
            'location' => 'Fellowship Hall',
            'requirements' => 'Must attend as a married couple.',
            'what_you_learn' => 'Biblical view of marriage, Communication skills, Conflict resolution, Romance and intimacy',
            'course_objectives' => 'Strengthen marriage bond, Improve communication, Resolve conflicts biblically, Grow in love and understanding',
            'current_enrollments' => 0,
            'has_certificate' => false,
            'min_attendance_for_certificate' => 0,
            'is_registration_open' => true,
            'sort_order' => 6,
        ]);

        Course::create([
            'title' => 'Financial Stewardship',
            'description' => 'Learn biblical principles of money management, budgeting, and generous giving.',
            'content' => 'Discover what the Bible teaches about money and learn practical skills for financial health and freedom.',
            'instructor' => 'James Anderson',
            'category' => 'Practical Living',
            'duration_weeks' => 6,
            'schedule' => 'Sundays 6:00-7:30 PM',
            'start_date' => '2025-09-15',
            'end_date' => '2025-10-20',
            'location' => 'Room 105',
            'requirements' => 'Open to all adults seeking financial wisdom.',
            'what_you_learn' => 'Biblical view of money, Budgeting strategies, Debt reduction plans, Principles of giving',
            'course_objectives' => 'Understand biblical stewardship, Create personal budget, Develop giving plan, Achieve financial peace',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 5,
            'is_registration_open' => true,
            'sort_order' => 7,
        ]);

        // Spiritual Growth
        Course::create([
            'title' => 'Prayer and Spiritual Disciplines',
            'description' => 'Deepen your relationship with God through prayer, meditation, and other spiritual disciplines.',
            'content' => 'Learn various methods of prayer and spiritual practices that will enrich your walk with God.',
            'instructor' => 'Sister Mary Catherine',
            'category' => 'Spiritual Growth',
            'duration_weeks' => 7,
            'schedule' => 'Mondays 7:00-8:00 PM',
            'start_date' => '2025-09-09',
            'end_date' => '2025-10-21',
            'location' => 'Prayer Chapel',
            'requirements' => 'Desire for deeper spiritual life.',
            'what_you_learn' => 'Various prayer methods, Scripture meditation, Fasting principles, Spiritual journaling',
            'course_objectives' => 'Develop consistent prayer life, Learn to hear God\'s voice, Practice spiritual disciplines, Grow in intimacy with God',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 6,
            'is_registration_open' => true,
            'sort_order' => 8,
        ]);

        Course::create([
            'title' => 'Evangelism and Discipleship',
            'description' => 'Learn how to share your faith naturally and make disciples who make disciples.',
            'content' => 'Practical training in sharing the Gospel and helping new believers grow in their faith.',
            'instructor' => 'Rev. Peter Williams',
            'category' => 'Evangelism',
            'duration_weeks' => 8,
            'schedule' => 'Wednesdays 7:30-9:00 PM',
            'start_date' => '2025-10-09',
            'end_date' => '2025-11-27',
            'location' => 'Room 203',
            'requirements' => 'Mature faith and heart for evangelism.',
            'what_you_learn' => 'Gospel presentation methods, Personal testimony development, Discipleship principles, Follow-up strategies',
            'course_objectives' => 'Share faith confidently, Make disciples effectively, Follow up with new believers, Multiply ministry impact',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 6,
            'is_registration_open' => true,
            'sort_order' => 9,
        ]);

        // Special Interest
        Course::create([
            'title' => 'Biblical Archaeology',
            'description' => 'Explore how archaeological discoveries illuminate and confirm biblical history.',
            'content' => 'Journey through the lands of the Bible and discover how archaeology confirms the historical accuracy of Scripture.',
            'instructor' => 'Dr. Robert Clarke',
            'category' => 'Biblical Studies',
            'duration_weeks' => 6,
            'schedule' => 'Saturdays 2:00-4:00 PM',
            'start_date' => '2025-11-02',
            'end_date' => '2025-12-07',
            'location' => 'Library',
            'requirements' => 'Interest in biblical history and archaeology.',
            'what_you_learn' => 'Major archaeological sites, Historical verification of biblical events, Ancient cultures and customs, Biblical geography',
            'course_objectives' => 'Strengthen faith through evidence, Understand biblical context, Learn about ancient civilizations, See Bible lands come alive',
            'current_enrollments' => 0,
            'has_certificate' => true,
            'min_attendance_for_certificate' => 5,
            'is_registration_open' => false,
            'sort_order' => 10,
        ]);
    }
}
