<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Notifications\SystemNotification;
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
     * @param  array<int, string>  $roleNames
     */
    public function execute(array $data, array $roleNames, User $actor): User
    {
        $tenantId = (int) ($data['tenant_id'] ?? 0);

        if (! $this->access->canAccessTenant($actor, $tenantId)) {
            throw new BusinessException('You are not allowed to create users for the selected company.', 403);
        }

        foreach ($roleNames as $roleName) {
            if (! $this->access->canManageRole($actor, $roleName)) {
                throw new BusinessException('You are not allowed to assign the selected role.', 403);
            }
        }

        return DB::transaction(function () use ($data, $roleNames, $actor): User {
            $user = User::query()->create([
                ...$data,
                'password' => Hash::make((string) $data['password']),
            ]);

            $user->syncRoles($roleNames);

            $this->logger->log('user.created', 'User created', $actor, $user, [
                'tenant_id' => $user->tenant_id,
                'roles' => $roleNames,
            ]);

            User::query()
                ->where('tenant_id', $user->tenant_id)
                ->whereKeyNot($actor->getKey())
                ->role(RoleName::CompanyAdministrator->value)
                ->get()
                ->each(fn (User $recipient) => $recipient->notify(new SystemNotification(
                    subject: 'New user created',
                    message: "{$user->full_name} was added to {$user->tenant?->name}.",
                    channels: ['database'],
                )));

            return $user->refresh();
        });
    }
}
