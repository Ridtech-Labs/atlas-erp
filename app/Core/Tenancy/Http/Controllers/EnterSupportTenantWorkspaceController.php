<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Controllers;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Services\SupportTenantWorkspaceService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnterSupportTenantWorkspaceController
{
    public function __invoke(Request $request, Tenant $tenant, SupportTenantWorkspaceService $workspace): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $workspace->enter($user, $tenant);

        return redirect('/admin');
    }
}
