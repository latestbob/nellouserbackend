<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\DoctorContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DoctorContactNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $doctorContact;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DoctorContact $doctorContact)
    {
        $this->doctorContact = $doctorContact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = $this->doctorContact->name ?: "{$this->doctorContact->user->firstname} {$this->doctorContact->user->lastname}";
        return (new MailMessage)
            ->greeting("Hi $notifiable->firstname")
            ->line("This is to notify you that one '{$name}' has contacted you on the nello platform.")
            ->line("Please login to your nello account to view their messages")
            ->action('View Message', 'https://admin.asknello.com/doctor/messages');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
