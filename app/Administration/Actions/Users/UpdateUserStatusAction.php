<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

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
        if ($this->access->wouldRemoveFinalAdministrativeAccess($actor, $subject, $subject->getRoleNames()->all())
            && $status !== UserStatus::Active) {
            throw new BusinessException('You cannot disable the final administrative account.', 422);
        }

        $subject->forceFill(['status' => $status])->save();

        $this->logger->log('user.status_changed', 'User status changed', $actor, $subject, [
            'tenant_id' => $subject->tenant_id,
            'status' => $status->value,
        ]);

        return $subject->refresh();
    }
}
