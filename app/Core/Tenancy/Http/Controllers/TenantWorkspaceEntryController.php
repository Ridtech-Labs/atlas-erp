<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Controllers;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\ActiveTenantResolver;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TenantWorkspaceEntryController
{
    public function __invoke(Request $request, Tenant $tenant, ActiveTenantResolver $tenantResolver): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User && $tenant->isActive(), 404);

        // A slug is only a stable customer-facing entry point; the authenticated context remains authoritative.
        abort_unless($tenantResolver->resolveFor($user)?->is($tenant) === true, 403);

        return redirect('/admin');
    }
}
