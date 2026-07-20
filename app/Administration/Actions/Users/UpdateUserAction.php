<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $roleNames
     */
    public function execute(User $subject, array $data, array $roleNames, User $actor): User
    {
        if (! $actor->can('users.update') || ! $this->access->canManageTenant($actor, $subject->tenant_id)) {
            throw new BusinessException('You are not allowed to update this user.', 403);
        }

        foreach ($roleNames as $roleName) {
            if (! $this->access->canAssignRole($actor, $roleName)) {
                throw new BusinessException('You are not allowed to assign the selected role.', 403);
            }
        }

        return DB::transaction(function () use ($subject, $data, $roleNames, $actor): User {
            $subject->fill(Arr::except($data, ['password']));

            if (filled($data['password'] ?? null)) {
                $subject->password = Hash::make((string) $data['password']);
            }

            $subject->save();
            $subject->syncRoles($roleNames);

            $this->logger->log('user.updated', 'User updated', $actor, $subject, [
                'tenant_id' => $subject->tenant_id,
                'roles' => $roleNames,
            ]);

            return $subject->refresh();
        });
    }
}
