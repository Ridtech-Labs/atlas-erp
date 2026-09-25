<?php

declare(strict_types=1);

namespace App\Administration\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AtlasAccountSetupNotification extends Notification
{
    public function __construct(
        public readonly string $token,
    ) {}

    /**
     * @return list<string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $companyName = $notifiable->companies()->withoutGlobalScopes()->orderBy('companies.name')->value('companies.name');
        $expiry = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject("You've been invited to Atlas ERP")
            ->greeting("Hello {$notifiable->first_name},")
            ->line($companyName ? "You've been invited to Atlas ERP for {$companyName}." : "You've been invited to Atlas ERP.")
            ->line('Set a password to activate your account and access your workspace.')
            ->action('Set up account', $url)
            ->line("This link expires in {$expiry} minutes.")
            ->line('If you were not expecting this invitation, you can safely ignore this email or contact your administrator.');
    }
}
