<?php

declare(strict_types=1);

namespace App\Fleet\Services;

use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\JobAssetAssignment;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class FleetUtilizationService
{
    /** @return Collection<int, mixed> */
    public function forCompany(int $tenantId, int $companyId, CarbonInterface $from, CarbonInterface $until): Collection
    {
        return JobAssetAssignment::query()->with('asset.type')->where('tenant_id', $tenantId)->where('company_id', $companyId)
            ->where('status', JobAssetAssignmentStatus::Returned->value)->whereBetween('returned_at', [$from, $until])
            ->whereColumn('returned_at', '>=', 'dispatched_at')->get()
            ->groupBy('fleet_asset_id')->map(function (Collection $assignments): array {
                $hours = $assignments->sum(fn (JobAssetAssignment $assignment): float => CarbonImmutable::parse((string) $assignment->getRawOriginal('dispatched_at'))->diffInSeconds(CarbonImmutable::parse((string) $assignment->getRawOriginal('returned_at'))) / 3600);
                $asset = $assignments->firstOrFail()->asset;

                if ($asset === null) {
                    return ['asset_number' => 'Unknown', 'asset_type' => null, 'completed_dispatches' => $assignments->count(), 'actual_hours' => $hours, 'average_hours' => $hours / max(1, $assignments->count())];
                }

                return ['asset_number' => (string) $asset->asset_number, 'asset_type' => is_string($asset->type?->name) ? $asset->type->name : null, 'completed_dispatches' => $assignments->count(), 'actual_hours' => $hours, 'average_hours' => $hours / max(1, $assignments->count())];
            })->values();
    }
}
