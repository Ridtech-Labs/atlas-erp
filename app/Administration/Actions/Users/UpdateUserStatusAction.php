<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;

class UpdateUserStatusAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(User $subject, UserStatus $status, User $actor): User
    {
        if (! $actor->can(PermissionName::UsersManageStatus->value) || ! $this->access->canManageTenant($actor, $subject->tenant_id)) {
            throw new BusinessException('You are not allowed to update this user status.', 403);
        }

        $subject->forceFill(['status' => $status->value])->save();

        $this->logger->log('user.status_changed', 'User status changed', $actor, $subject, [
            'tenant_id' => $subject->tenant_id,
            'status' => $status->value,
        ]);

        return $subject->refresh();
    }
}
