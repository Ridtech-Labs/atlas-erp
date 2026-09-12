<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Support\TenantContext;
use App\Models\User;
use App\Operations\Services\OperationsControlService;
use Filament\Widgets\Widget;

class OperationsControlWidget extends Widget
{
    protected string $view = 'filament.widgets.operations-control-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        $user = auth()->user();
        $access = app(AdministrationAccessService::class);
        $companyId = $user instanceof User ? $access->activeCompanyId($user) : null;
        $allowed = $user instanceof User && is_int($companyId) && ($user->hasRole(RoleName::CompanyAdministrator->value) || $user->hasRole(RoleName::OperationsManager->value) || $access->isSuperAdministrator($user));

        return ['visible' => $allowed, 'control' => $allowed ? app(OperationsControlService::class)->forCompany(app(TenantContext::class)->id() ?? $user->tenant_id, $companyId) : []];
    }
}
