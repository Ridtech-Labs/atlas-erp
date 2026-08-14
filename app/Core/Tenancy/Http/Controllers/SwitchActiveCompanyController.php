<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Controllers;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchActiveCompanyController
{
    public function __invoke(Request $request, AdministrationAccessService $access): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);
        abort_if($access->isPlatformSession($user), 403);

        $validated = $request->validate([
            'company_id' => ['required', 'integer'],
        ]);

        $tenantId = $access->activeTenantId($user) ?? $user->tenant_id;
        $company = Company::query()
            ->whereKey((int) $validated['company_id'])
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        abort_unless($access->authorizedCompanies($user)->contains('id', $company->getKey()), 403);

        session(['active_company_id' => $company->getKey()]);

        return back();
    }
}
