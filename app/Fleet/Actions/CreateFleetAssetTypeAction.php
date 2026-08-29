<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateFleetAssetTypeAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): FleetAssetType
    {
        $company = $this->activeCompany($actor);
        $name = $this->normalizedName($data['name'] ?? null);
        $category = $this->category($data['category'] ?? null);

        return DB::transaction(function () use ($actor, $company, $name, $category, $data): FleetAssetType {
            $this->ensureNameIsAvailable($company, $name);

            $type = FleetAssetType::query()->create([
                'tenant_id' => $company->tenant_id,
                'company_id' => $company->getKey(),
                'name' => $name,
                'category' => $category,
                'is_active' => (bool) ($data['is_active'] ?? true),
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('fleet_asset_type.created', 'Fleet asset type created', $actor, $type, [
                'tenant_id' => $type->tenant_id,
                'company_id' => $type->company_id,
                'fleet_asset_type_id' => $type->getKey(),
            ]);

            return $type->refresh();
        });
    }

    private function activeCompany(User $actor): Company
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssetsManage->value)) {
            throw new BusinessException('You are not allowed to manage Fleet asset types.', 403);
        }

        $company = $this->access->activeCompany($actor);

        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $company->tenant_id)) {
            throw new BusinessException('Select an authorized company before managing Fleet asset types.', 403);
        }

        return $company;
    }

    private function normalizedName(mixed $value): string
    {
        $name = is_string($value) ? Str::squish($value) : '';

        if ($name === '') {
            throw new BusinessException('Enter a Fleet asset type name.', 422);
        }

        return $name;
    }

    private function category(mixed $value): FleetAssetCategory
    {
        $category = is_string($value) ? FleetAssetCategory::tryFrom($value) : null;

        if (! $category instanceof FleetAssetCategory) {
            throw new BusinessException('Select a valid Fleet asset category.', 422);
        }

        return $category;
    }

    private function ensureNameIsAvailable(Company $company, string $name, ?FleetAssetType $ignore = null): void
    {
        $query = FleetAssetType::withTrashed()
            ->where('company_id', $company->getKey())
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)]);

        if ($ignore instanceof FleetAssetType) {
            $query->whereKeyNot($ignore->getKey());
        }

        if ($query->exists()) {
            throw new BusinessException('A Fleet asset type with this name already exists for the active company.', 422);
        }
    }
}
