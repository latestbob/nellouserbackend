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

    private $code;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', TextMessageChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Hi $notifiable->firstname")
            ->line('Please verify your email address by visiting the link below.')
            ->line("Your verification code is $this->code")
            ->action('Verify', url("https://asknello.com/$notifiable->user_type/contact/confirm"))
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
        Please verify that it is you. \n
        Copy the following code and click the link below to confirm your identity: 
        $this->code https://asknello.com/$notifiable->user_type/contact/confirm.\n
        If this was not you, please ignore. \n
        Yours sincerely,\n
        Nello ";
        return $sms;
    }
}
