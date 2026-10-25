<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Controllers;

use App\Core\Tenancy\Models\Tenant;
use Illuminate\Http\RedirectResponse;

class TenantLoginEntryController
{
    public function __invoke(Tenant $tenant): RedirectResponse
    {
        abort_unless($tenant->isActive(), 404);

        $loginUrl = filament()->getLoginUrl([
            'tenant' => $tenant->slug,
        ]);

        abort_unless(is_string($loginUrl), 404);

        return redirect()->to($loginUrl);
    }
}
