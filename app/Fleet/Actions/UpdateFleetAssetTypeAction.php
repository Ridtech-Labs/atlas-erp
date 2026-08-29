<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateFleetAssetTypeAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(FleetAssetType $type, array $data, User $actor): FleetAssetType
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssetsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $type->company_id, $type->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Fleet asset type.', 403);
        }

        $name = $this->normalizedName($data['name'] ?? null);
        $category = $this->category($data['category'] ?? null);

        return DB::transaction(function () use ($type, $data, $actor, $name, $category): FleetAssetType {
            $this->ensureNameIsAvailable($type, $name);
            $type->fill([
                'name' => $name,
                'category' => $category,
                'is_active' => (bool) ($data['is_active'] ?? false),
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('fleet_asset_type.updated', 'Fleet asset type updated', $actor, $type, [
                'tenant_id' => $type->tenant_id,
                'company_id' => $type->company_id,
                'fleet_asset_type_id' => $type->getKey(),
            ]);

            return $type->refresh();
        });
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

    private function ensureNameIsAvailable(FleetAssetType $type, string $name): void
    {
        if (FleetAssetType::withTrashed()
            ->where('company_id', $type->company_id)
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->whereKeyNot($type->getKey())
            ->exists()) {
            throw new BusinessException('A Fleet asset type with this name already exists for the active company.', 422);
        }
    }
}
