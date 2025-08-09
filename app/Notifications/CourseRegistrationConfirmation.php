<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseRegistrationConfirmation extends Notification // Temporarily disable queuing for testing
{
    use Queueable;

    public $course;
    public $enrollment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Course $course, CourseEnrollment $enrollment)
    {
        $this->course = $course;
        $this->enrollment = $enrollment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Course Registration Confirmation - ' . $this->course->title)
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Thank you for registering for **' . $this->course->title . '**.')
            ->line('**Course Details:**')
            ->line('Start Date: ' . $this->course->start_date?->format('F j, Y'))
            ->line('End Date: ' . $this->course->end_date?->format('F j, Y'))
            ->line('Location: ' . ($this->course->location ?: 'TBA'))
            ->line('Instructor: ' . ($this->course->instructor ?: 'TBA'))
            ->line('')
            ->line('We will contact you soon with additional details about the course.')
            ->action('View Course Details', url('/courses/' . $this->course->slug))
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->salutation('Blessings, The CityLife Team');
    }

    /**
     * Get the SMS message content.
     */
    public function getSmsMessage(object $notifiable): string
    {
        $message = "Hello {$notifiable->first_name}! You've successfully registered for {$this->course->title}. ";
        $message .= "Start date: " . $this->course->start_date?->format('M j, Y') . ". ";
        $message .= "We'll contact you soon with more details. - CityLife Team";

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'enrollment_id' => $this->enrollment->id,
            'message' => 'You have successfully registered for ' . $this->course->title,
        ];
    }
}
