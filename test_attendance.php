<?php

use App\Models\CourseEnrollment;
use App\Models\LessonAttendance;

// Get an enrollment to test with
$enrollment = CourseEnrollment::first();
if ($enrollment) {
    echo "Testing enrollment ID: {$enrollment->id}\n";
    echo "Current progress: {$enrollment->progress_percentage}%\n";
    echo "Current completed lessons: {$enrollment->completed_lessons}\n";

    // Get a lesson from the course
    $lesson = $enrollment->course->lessons()->first();
    if ($lesson) {
        echo "Creating attendance record for lesson: {$lesson->title}\n";

        // Create an attendance record
        $attendance = LessonAttendance::create([
            'course_enrollment_id' => $enrollment->id,
            'course_lesson_id' => $lesson->id,
            'attended' => true,
            'attendance_date' => now(),
            'marked_by' => 1,
            'notes' => 'Test attendance record'
        ]);

        // Refresh the enrollment to see updated progress
        $enrollment->refresh();
        echo "New progress: {$enrollment->progress_percentage}%\n";
        echo "New completed lessons: {$enrollment->completed_lessons}\n";
    } else {
        echo "No lessons found for this course\n";
    }
} else {
    echo "No enrollments found\n";
}
