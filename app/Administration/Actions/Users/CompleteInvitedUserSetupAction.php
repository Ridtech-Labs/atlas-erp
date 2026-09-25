<?php

declare(strict_types=1);

namespace App\Administration\Actions\Users;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompleteInvitedUserSetupAction
{
    public function __construct(
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(User $user, string $password): void
    {
        DB::transaction(function () use ($user, $password): void {
            $wasInvited = $user->getRawOriginal('status') === UserStatus::Invited->value;

            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
                ...($wasInvited ? [
                    'status' => UserStatus::Active->value,
                    'email_verified_at' => now(),
                ] : []),
            ])->save();

            if ($wasInvited) {
                $this->logger->log('user.invitation_accepted', 'User invitation accepted', $user, $user, [
                    'tenant_id' => $user->tenant_id,
                ]);
            }
        });
    }
}
