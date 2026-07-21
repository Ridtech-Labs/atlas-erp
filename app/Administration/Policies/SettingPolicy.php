<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::SettingsView->value);
    }

    public function update(User $user): bool
    {
        return $user->can(PermissionName::SettingsUpdate->value);
    }
}
