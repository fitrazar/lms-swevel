<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantEnrollmentNotification extends Notification
{
    use Queueable;

    private $enrollment, $title, $message;
    /**
     * Create a new notification instance.
     */
    public function __construct($enrollment, $title, $message)
    {
        $this->enrollment = $enrollment;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase(object $notifiable)
    {
        $course = $this->enrollment->course->slug;
        $topic = $this->enrollment->course->topics[0]->slug;
        return [
            'id' => $this->enrollment->id,
            'participant_id' => $notifiable->participant->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'enrollment',
            'link' => route('course.read', [$course, $topic]),
        ];
    }
}
