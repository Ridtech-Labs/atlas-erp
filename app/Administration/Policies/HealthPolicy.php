<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class HealthPolicy
{
    public function viewAny(User $user): bool
    {
        try {
            return $user->hasPermissionTo(PermissionName::HealthView->value);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
