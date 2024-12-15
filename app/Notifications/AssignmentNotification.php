<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentNotification extends Notification
{
    use Queueable;

    private $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct($assignment)
    {
        $this->assignment = $assignment;
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
        $course = $this->assignment->material->topic->course->slug;
        $topic = $this->assignment->material->topic->slug;
        return (new MailMessage)
            ->greeting('Halo ' . $notifiable->email)
            ->subject('Reminder: Deadline Tugas')
            ->line('Tugas "' . $this->assignment->title . '" kurang dari 1 hari lagi.')
            ->action('Lihat Tugas', route('course.read', [$course, $topic]))
            ->line('Ayo cepat submit.');
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

        return [
            'message' => 'Kamu belum mengumpulkan tugas nih',
            'type' => 'assignment'
        ];

    }
}
