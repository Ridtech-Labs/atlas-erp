<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class SendUserInvitationAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(User $subject, User $actor, bool $isResend = false): void
    {
        if (! $this->access->canAccessManagedUser($actor, $subject)
            || ! $actor->can($isResend ? 'users.update' : 'users.create')) {
            throw new BusinessException('You are not allowed to manage this invitation.', 403);
        }

        if ($subject->getRawOriginal('status') !== UserStatus::Invited->value) {
            throw new BusinessException('Only invited users can receive an account setup link.', 422);
        }

        $status = Password::sendResetLink(['email' => $subject->email]);

        if ($status === Password::RESET_THROTTLED) {
            throw new BusinessException('An account setup link was sent recently. Please wait before resending it.', 429);
        }

        if ($status !== Password::RESET_LINK_SENT) {
            throw new BusinessException('The account setup link could not be sent. Confirm the email address and try again.', 422);
        }

        if ($isResend) {
            $this->logger->log('user.invitation_resent', 'User invitation resent', $actor, $subject, [
                'tenant_id' => $subject->tenant_id,
            ]);
        }
    }
}
