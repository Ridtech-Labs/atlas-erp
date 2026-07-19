<?php

declare(strict_types=1);

namespace App\Core\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<int, string>  $channels
     */
    public function __construct(
        private readonly string $subject,
        private readonly string $message,
        private readonly array $channels = ['database'],
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->message);
    }
}
