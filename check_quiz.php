<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Course;

echo "Looking for courses with quiz data...\n";

$courses = Course::with('lessons')->get();

foreach($courses as $course) {
    echo "Course: " . $course->title . "\n";
    $lessonsWithQuiz = $course->lessons->whereNotNull('quiz_questions');

    if($lessonsWithQuiz->count() > 0) {
        echo "  Lessons with quizzes: " . $lessonsWithQuiz->count() . "\n";
        foreach($lessonsWithQuiz as $lesson) {
            $questions = json_decode($lesson->quiz_questions, true);
            echo "    - " . $lesson->title . " (" . count($questions) . " questions)\n";
        }
    } else {
        echo "  No lessons with quizzes.\n";
    }
}

echo "Done.\n";
