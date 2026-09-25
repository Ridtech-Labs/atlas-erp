<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class RevokeUserInvitationAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(User $subject, User $actor): User
    {
        if (! $actor->can('users.update') || ! $this->access->canAccessManagedUser($actor, $subject)) {
            throw new BusinessException('You are not allowed to revoke this invitation.', 403);
        }

        if ($subject->getRawOriginal('status') !== UserStatus::Invited->value) {
            throw new BusinessException('Only pending invitations can be revoked.', 422);
        }

        return DB::transaction(function () use ($subject, $actor): User {
            Password::broker()->deleteToken($subject);
            $subject->forceFill(['status' => UserStatus::Inactive->value])->save();

            $this->logger->log('user.invitation_revoked', 'User invitation revoked', $actor, $subject, [
                'tenant_id' => $subject->tenant_id,
            ]);

            return $subject->refresh();
        });
    }
}
