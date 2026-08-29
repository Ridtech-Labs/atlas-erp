<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateFleetAssetAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(FleetAsset $asset, array $data, User $actor): FleetAsset
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssetsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $asset->company_id, $asset->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Fleet asset.', 403);
        }

        $assetNumber = $this->assetNumber($data['asset_number'] ?? null);
        $registrationNumber = $this->registrationNumber($data['registration_number'] ?? null);
        $status = $this->status($data['operational_status'] ?? null);
        $type = $this->assetType($data['fleet_asset_type_id'] ?? null, $asset);

        return DB::transaction(function () use ($asset, $data, $actor, $assetNumber, $registrationNumber, $status, $type): FleetAsset {
            $this->ensureIdentityIsAvailable($asset, $assetNumber, $registrationNumber);
            $statusChanged = $asset->getRawOriginal('operational_status') !== $status->value;

            $asset->fill([
                ...Arr::only($data, ['make', 'model', 'serial_number', 'notes']),
                'fleet_asset_type_id' => $type->getKey(),
                'asset_number' => $assetNumber,
                'registration_number' => $registrationNumber,
                'operational_status' => $status,
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log(
                $statusChanged ? 'fleet_asset.status_changed' : 'fleet_asset.updated',
                $statusChanged ? 'Fleet asset operational status changed' : 'Fleet asset updated',
                $actor,
                $asset,
                [
                    'tenant_id' => $asset->tenant_id,
                    'company_id' => $asset->company_id,
                    'fleet_asset_id' => $asset->getKey(),
                    'asset_number' => $asset->asset_number,
                    'operational_status' => $status->value,
                ],
            );

            return $asset->refresh();
        });
    }

    private function assetType(mixed $typeId, FleetAsset $asset): FleetAssetType
    {
        if (! is_numeric($typeId)) {
            throw new BusinessException('Select a Fleet asset type.', 422);
        }

        $type = FleetAssetType::query()
            ->whereKey((int) $typeId)
            ->where('tenant_id', $asset->tenant_id)
            ->where('company_id', $asset->company_id)
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

    private function ensureIdentityIsAvailable(FleetAsset $asset, string $assetNumber, ?string $registrationNumber): void
    {
        if (FleetAsset::withTrashed()
            ->where('company_id', $asset->company_id)
            ->where('asset_number', $assetNumber)
            ->whereKeyNot($asset->getKey())
            ->exists()) {
            throw new BusinessException('This Fleet asset number already exists for the active company.', 422);
        }

        if ($registrationNumber !== null && FleetAsset::withTrashed()
            ->where('company_id', $asset->company_id)
            ->where('registration_number', $registrationNumber)
            ->whereKeyNot($asset->getKey())
            ->exists()) {
            throw new BusinessException('This registration number already exists for the active company.', 422);
        }
    }
}
