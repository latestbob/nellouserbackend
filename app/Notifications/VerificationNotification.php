<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Channels\TextMessageChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; //, TextMessageChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
        return (new MailMessage)
            ->greeting("Hi $user->firstname")
            ->line('Please verify your email address by visiting the link below.')
            //->line("Your verification code is $this->code")
            ->action('Verify', url("https://mw.asknello.com/api/auth/verify/$user->token"))
            ->line('Thank you for choosing Nello!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toTextMessage($notifiable)
    {
        $sms =  "
        Please verify that it is you.
        Your verification code is $this->code.
        If this was not you, please ignore.
        Yours sincerely,
        Nello ";
        return $sms;
    }
}
