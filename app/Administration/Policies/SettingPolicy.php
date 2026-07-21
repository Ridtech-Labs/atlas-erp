<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        try {
            return $user->hasPermissionTo(PermissionName::SettingsView->value);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }

    public function update(User $user): bool
    {
        try {
            return $user->hasPermissionTo(PermissionName::SettingsUpdate->value);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
