<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPasswordResetNotification extends Notification
{
    use Queueable;

    protected $newPassword;

    public function __construct(string $newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Password Reset - Members Portal')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Your password has been reset by the administrator.')
            ->line('Your new password is: **' . $this->newPassword . '**')
            ->line('Please login to the Members Portal and change your password immediately for security reasons.')
            ->action('Login to Portal', url('/login'))
            ->line('If you did not request this password reset, please contact the administrator immediately.')
            ->salutation('Regards, Members Portal Team');
    }
}
