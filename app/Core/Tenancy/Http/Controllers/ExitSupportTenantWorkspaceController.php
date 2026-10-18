<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Controllers;

use App\Core\Tenancy\Services\SupportTenantWorkspaceService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExitSupportTenantWorkspaceController
{
    public function __invoke(Request $request, SupportTenantWorkspaceService $workspace): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $workspace->exit($user);

        return redirect('/admin');
    }
}
