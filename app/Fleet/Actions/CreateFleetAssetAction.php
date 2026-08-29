<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateFleetAssetAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): FleetAsset
    {
        $company = $this->activeCompany($actor);
        $assetNumber = $this->assetNumber($data['asset_number'] ?? null);
        $registrationNumber = $this->registrationNumber($data['registration_number'] ?? null);
        $status = $this->status($data['operational_status'] ?? null);
        $type = $this->assetType($data['fleet_asset_type_id'] ?? null, $company);

        return DB::transaction(function () use ($data, $actor, $company, $assetNumber, $registrationNumber, $status, $type): FleetAsset {
            $this->ensureIdentityIsAvailable($company, $assetNumber, $registrationNumber);

            $asset = FleetAsset::query()->create([
                ...Arr::only($data, ['make', 'model', 'serial_number', 'notes']),
                'tenant_id' => $company->tenant_id,
                'company_id' => $company->getKey(),
                'fleet_asset_type_id' => $type->getKey(),
                'asset_number' => $assetNumber,
                'registration_number' => $registrationNumber,
                'operational_status' => $status,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('fleet_asset.created', 'Fleet asset created', $actor, $asset, [
                'tenant_id' => $asset->tenant_id,
                'company_id' => $asset->company_id,
                'fleet_asset_id' => $asset->getKey(),
                'asset_number' => $asset->asset_number,
            ]);

            return $asset->refresh();
        });
    }

    private function activeCompany(User $actor): Company
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssetsManage->value)) {
            throw new BusinessException('You are not allowed to manage Fleet assets.', 403);
        }

        $company = $this->access->activeCompany($actor);

        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $company->tenant_id)) {
            throw new BusinessException('Select an authorized company before managing Fleet assets.', 403);
        }

        return $company;
    }

    private function assetType(mixed $typeId, Company $company): FleetAssetType
    {
        if (! is_numeric($typeId)) {
            throw new BusinessException('Select a Fleet asset type.', 422);
        }

        $type = FleetAssetType::query()
            ->whereKey((int) $typeId)
            ->where('tenant_id', $company->tenant_id)
            ->where('company_id', $company->getKey())
            ->where('is_active', true)
            ->first();

        if (! $type instanceof FleetAssetType) {
            throw new BusinessException('The selected Fleet asset type is not available in the active company.', 422);
        }

        return $type;
    }

    private function assetNumber(mixed $value): string
    {
        $assetNumber = is_string($value) ? Str::upper(Str::squish($value)) : '';

        if ($assetNumber === '') {
            throw new BusinessException('Enter a stable Fleet asset number.', 422);
        }

        return $assetNumber;
    }

    private function registrationNumber(mixed $value): ?string
    {
        $registrationNumber = is_string($value) ? Str::upper(Str::squish($value)) : '';

        return $registrationNumber === '' ? null : $registrationNumber;
    }

    private function status(mixed $value): FleetAssetOperationalStatus
    {
        $status = is_string($value) ? FleetAssetOperationalStatus::tryFrom($value) : null;

        if (! $status instanceof FleetAssetOperationalStatus) {
            throw new BusinessException('Select a valid operational status.', 422);
        }

        return $status;
    }

    private function ensureIdentityIsAvailable(Company $company, string $assetNumber, ?string $registrationNumber, ?FleetAsset $ignore = null): void
    {
        $assetNumberQuery = FleetAsset::withTrashed()
            ->where('company_id', $company->getKey())
            ->where('asset_number', $assetNumber);

        if ($ignore instanceof FleetAsset) {
            $assetNumberQuery->whereKeyNot($ignore->getKey());
        }

        if ($assetNumberQuery->exists()) {
            throw new BusinessException('This Fleet asset number already exists for the active company.', 422);
        }

        if ($registrationNumber === null) {
            return;
        }

        $registrationQuery = FleetAsset::withTrashed()
            ->where('company_id', $company->getKey())
            ->where('registration_number', $registrationNumber);

        if ($ignore instanceof FleetAsset) {
            $registrationQuery->whereKeyNot($ignore->getKey());
        }

        if ($registrationQuery->exists()) {
            throw new BusinessException('This registration number already exists for the active company.', 422);
        }
    }
}
