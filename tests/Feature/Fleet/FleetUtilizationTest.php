<?php

use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\FleetAssetType;
use App\Fleet\Models\JobAssetAssignment;
use App\Fleet\Services\FleetUtilizationService;
use App\Operations\Models\Job;
use Carbon\CarbonImmutable;

test('utilization uses positive dispatch to return duration and excludes invalid historical ordering', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $type = FleetAssetType::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'name' => 'Reach Stacker']);
    $asset = FleetAsset::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_type_id' => $type->getKey(), 'asset_number' => 'RS-003']);
    $job = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    JobAssetAssignment::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'job_id' => $job->getKey(), 'fleet_asset_id' => $asset->getKey(), 'status' => JobAssetAssignmentStatus::Returned, 'dispatched_at' => '2026-09-04 02:13:00', 'returned_at' => '2026-09-07 02:00:00']);
    JobAssetAssignment::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'job_id' => $job->getKey(), 'fleet_asset_id' => $asset->getKey(), 'status' => JobAssetAssignmentStatus::Returned, 'dispatched_at' => '2026-09-08 02:00:00', 'returned_at' => '2026-09-08 01:00:00']);
    $rows = app(FleetUtilizationService::class)->forCompany($tenant->getKey(), $company->getKey(), CarbonImmutable::parse('2026-09-01')->startOfDay(), CarbonImmutable::parse('2026-09-30')->endOfDay());
    expect($rows)->toHaveCount(1)->and($rows->first()['completed_dispatches'])->toBe(1)->and(round((float) $rows->first()['actual_hours'], 4))->toBe(71.7833)->and(round((float) $rows->first()['average_hours'], 4))->toBe(71.7833);
});
