<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $roleNames
     */
    public function execute(array $data, array $roleNames, User $actor): User
    {
        $tenantId = (int) ($data['tenant_id'] ?? $actor->tenant_id);

        if (! $actor->can('users.create') || ! $this->access->canManageTenant($actor, $tenantId)) {
            throw new BusinessException('You are not allowed to create users for this company.', 403);
        }

        foreach ($roleNames as $roleName) {
            if (! $this->access->canAssignRole($actor, $roleName)) {
                throw new BusinessException('You are not allowed to assign the selected role.', 403);
            }
        }

        return DB::transaction(function () use ($data, $roleNames, $actor, $tenantId): User {
            $user = User::query()->create([
                'tenant_id' => $tenantId,
                'first_name' => (string) $data['first_name'],
                'last_name' => (string) $data['last_name'],
                'email' => (string) $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'] ?? UserStatus::Active->value,
                'password' => Hash::make((string) $data['password']),
            ]);

            $user->syncRoles($roleNames);

            $this->logger->log('user.created', 'User created', $actor, $user, [
                'tenant_id' => $user->tenant_id,
                'roles' => $roleNames,
            ]);

            return $user->refresh();
        });
    }
}
