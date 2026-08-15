<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Notifications\SystemNotification;
use App\Core\Tenancy\Models\Company;
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
        $company = $this->resolveTrustedCompany($data, $actor);
        $tenantId = $company->tenant_id;

        if (! $actor->can('users.create') || ! $this->access->canAccessTenant($actor, $tenantId)) {
            throw new BusinessException('You are not allowed to create users for the selected company.', 403);
        }

        foreach ($roleNames as $roleName) {
            if (! $this->access->canManageRole($actor, $roleName)) {
                throw new BusinessException('You are not allowed to assign the selected role.', 403);
            }
        }

        return DB::transaction(function () use ($data, $roleNames, $actor, $tenantId, $company): User {
            $user = User::query()->create([
                'tenant_id' => $tenantId,
                'first_name' => (string) $data['first_name'],
                'last_name' => (string) $data['last_name'],
                'email' => (string) $data['email'],
                'phone' => $data['phone'] ?? null,
                'avatar_path' => $data['avatar_path'] ?? null,
                'status' => $data['status'] ?? UserStatus::Active->value,
                'email_verified_at' => now(),
                'password' => Hash::make((string) $data['password']),
            ]);

            $user->companies()->sync([$company->getKey()]);
            $user->syncRoles($roleNames);

            $this->logger->log('user.created', 'User created', $actor, $user, [
                'tenant_id' => $user->tenant_id,
                'company_id' => $company->getKey(),
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

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveTrustedCompany(array $data, User $actor): Company
    {
        if (! $this->access->isSuperAdministrator($actor)) {
            $company = $this->access->activeCompany($actor);

            if (! $company instanceof Company) {
                throw new BusinessException('Select an active company before creating users.', 422);
            }

            if (isset($data['tenant_id']) && (int) $data['tenant_id'] !== $company->tenant_id) {
                throw new BusinessException('You cannot create users for another tenant account.', 403);
            }

            if (isset($data['company_id']) && (int) $data['company_id'] !== $company->getKey()) {
                throw new BusinessException('You cannot create users for another company.', 403);
            }

            return $company;
        }

        $companyId = isset($data['company_id']) ? (int) $data['company_id'] : null;

        if (is_int($companyId) && $companyId > 0) {
            $company = Company::query()->find($companyId);

            if (! $company instanceof Company) {
                throw new BusinessException('The selected company could not be found.', 422);
            }

            if (isset($data['tenant_id']) && (int) $data['tenant_id'] !== $company->tenant_id) {
                throw new BusinessException('The selected company does not belong to the selected tenant account.', 422);
            }

            return $company;
        }

        $tenantId = isset($data['tenant_id']) ? (int) $data['tenant_id'] : $actor->tenant_id;
        $company = Company::query()
            ->where('tenant_id', $tenantId)
            ->where('is_default', true)
            ->first();

        if (! $company instanceof Company) {
            throw new BusinessException('Select a company before creating this user.', 422);
        }

        return $company;
    }
}
