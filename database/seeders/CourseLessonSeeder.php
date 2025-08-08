<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseLesson;
use App\Models\Course;

class CourseLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first few courses to add lessons to
        $courses = Course::take(3)->get();

        if ($courses->isEmpty()) {
            $this->command->info('No courses found. Please run CourseSeeder first.');
            return;
        }

        foreach ($courses as $course) {
            // Create 6 lessons for each course
            for ($i = 1; $i <= 6; $i++) {
                CourseLesson::create([
                    'course_id' => $course->id,
                    'title' => $this->getLessonTitle($course->title, $i),
                    'slug' => \Illuminate\Support\Str::slug($this->getLessonTitle($course->title, $i)),
                    'description' => $this->getLessonDescription($course->title, $i),
                    'content' => $this->getLessonContent($course->title, $i),
                    'lesson_number' => $i,
                    'quiz_questions' => $this->getQuizQuestions($i),
                    'is_published' => true,
                    'available_date' => $course->start_date?->addWeeks($i - 1),
                ]);
            }
        }
    }

    private function getLessonTitle($courseTitle, $lessonNumber)
    {
        $lessons = [
            'Introduction to Christianity' => [
                1 => 'Who is God? Understanding the Trinity',
                2 => 'Understanding Sin and Salvation',
                3 => 'The Life and Ministry of Jesus Christ',
                4 => 'The Holy Spirit and His Work',
                5 => 'Prayer and Bible Study',
                6 => 'Living as a Christian'
            ],
            'Old Testament Survey' => [
                1 => 'Creation and the Early World',
                2 => 'Abraham and the Patriarchs',
                3 => 'Moses and the Exodus',
                4 => 'The Kingdom of Israel',
                5 => 'The Prophets and Exile',
                6 => 'Return and Restoration'
            ],
            'Christian Leadership Principles' => [
                1 => 'Biblical Foundation of Leadership',
                2 => 'Character and Integrity',
                3 => 'Vision and Communication',
                4 => 'Team Building and Delegation',
                5 => 'Conflict Resolution',
                6 => 'Leading Change and Growth'
            ]
        ];

        foreach ($lessons as $title => $lessonList) {
            if (str_contains($courseTitle, $title)) {
                return $lessonList[$lessonNumber] ?? "Lesson {$lessonNumber}";
            }
        }

        return "Lesson {$lessonNumber}: " . $this->getGenericLessonTitle($lessonNumber);
    }

    private function getGenericLessonTitle($lessonNumber)
    {
        $genericTitles = [
            1 => 'Introduction and Foundations',
            2 => 'Core Concepts and Principles',
            3 => 'Practical Applications',
            4 => 'Advanced Understanding',
            5 => 'Real-World Practice',
            6 => 'Integration and Next Steps'
        ];

        return $genericTitles[$lessonNumber] ?? "Advanced Topic {$lessonNumber}";
    }

    private function getLessonDescription($courseTitle, $lessonNumber)
    {
        $descriptions = [
            1 => 'This introductory lesson establishes the foundational concepts and prepares students for the journey ahead.',
            2 => 'Building on the foundation, this lesson explores core principles and their biblical basis.',
            3 => 'Students will learn practical applications and how to implement these concepts in daily life.',
            4 => 'This lesson delves deeper into advanced understanding and theological implications.',
            5 => 'Hands-on practice and real-world scenarios help students apply their learning.',
            6 => 'The final lesson focuses on integration and preparing for continued growth and ministry.'
        ];

        return $descriptions[$lessonNumber];
    }

    private function getLessonContent($courseTitle, $lessonNumber)
    {
        return "<h2>Lesson Overview</h2>
<p>Welcome to lesson {$lessonNumber} of {$courseTitle}. In this session, we will explore important concepts that will help you grow in your faith and understanding.</p>

<h3>Learning Objectives</h3>
<ul>
<li>Understand the key biblical principles covered in this lesson</li>
<li>Apply these principles to your personal faith journey</li>
<li>Prepare for practical implementation in daily life</li>
</ul>

<h3>Key Scripture References</h3>
<p>We will be studying several key passages that relate to today's topic. Please have your Bible ready for reference.</p>

<h3>Discussion Points</h3>
<p>Throughout this lesson, we will have opportunities for group discussion and personal reflection. Come prepared to share your thoughts and learn from others.</p>

<h3>Practical Application</h3>
<p>Each lesson includes practical ways to apply what you learn. We encourage you to implement these practices in your daily walk with Christ.</p>";
    }

    private function getQuizQuestions($lessonNumber)
    {
        $questions = [
            [
                "question" => "What are the main learning objectives for this lesson?",
                "type" => "multiple_choice",
                "options" => ["A) Understanding key principles", "B) Practical application", "C) Spiritual growth", "D) All of the above"],
                "correct_answer" => "D"
            ],
            [
                "question" => "Name two key Scripture passages discussed in this lesson.",
                "type" => "short_answer"
            ],
            [
                "question" => "How can you apply this lesson's principles in your daily life?",
                "type" => "essay"
            ]
        ];

        return json_encode($questions);
    }
}
