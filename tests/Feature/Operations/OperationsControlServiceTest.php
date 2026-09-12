<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Pages\Dashboard;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingSourceType;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingBatchLine;
use App\Finance\Models\BillingRecord;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\FleetAssetType;
use App\Fleet\Models\JobAssetAssignment;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;
use App\Operations\Services\OperationsControlService;
use Illuminate\Support\Str;

test('operations control returns company-scoped job type and fleet counts', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $otherCompany = $this->company($tenant);

    foreach ([JobStatus::Draft, JobStatus::PendingApproval, JobStatus::Approved, JobStatus::Scheduled, JobStatus::InProgress, JobStatus::Completed, JobStatus::Cancelled] as $status) {
        Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'status' => $status, 'job_type' => $status === JobStatus::Draft ? JobType::Trucking : JobType::HeavyMachinery]);
    }
    Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $otherCompany->getKey(), 'status' => JobStatus::Draft, 'job_type' => JobType::Trucking]);

    $type = FleetAssetType::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $available = FleetAsset::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_type_id' => $type->getKey(), 'operational_status' => FleetAssetOperationalStatus::Available]);
    $assigned = FleetAsset::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_type_id' => $type->getKey(), 'operational_status' => FleetAssetOperationalStatus::Available]);
    $dispatched = FleetAsset::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_type_id' => $type->getKey(), 'operational_status' => FleetAssetOperationalStatus::Available]);
    FleetAsset::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_type_id' => $type->getKey(), 'operational_status' => FleetAssetOperationalStatus::OutOfService]);
    JobAssetAssignment::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_id' => $assigned->getKey(), 'status' => JobAssetAssignmentStatus::Assigned]);
    JobAssetAssignment::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'fleet_asset_id' => $dispatched->getKey(), 'status' => JobAssetAssignmentStatus::Dispatched]);

    $control = app(OperationsControlService::class)->forCompany($tenant->getKey(), $company->getKey());

    expect($control['jobs'])->toMatchArray(['total' => 7, 'draft' => 1, 'pending_approval' => 1, 'approved_ready' => 2, 'in_progress' => 1, 'completed' => 1, 'cancelled' => 1])
        ->and($control['types'])->toMatchArray(['heavy_machinery' => 6, 'trucking' => 1])
        ->and($control['fleet'])->toMatchArray(['available' => 1, 'assigned' => 1, 'dispatched' => 1, 'out_of_service' => 1]);
});

test('operations control dashboard is available to operations managers but not data entry clerks', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $this->actingAs($operations)->get(Dashboard::getUrl())->assertOk()->assertSee('Operations Control');

    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($clerk)->get(Dashboard::getUrl())->assertOk()->assertDontSee('Operations Control');
});

test('operations control excludes batched evidence and prepared batches with Billing Records', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $job = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::HeavyMachinery]);
    $card = JobCard::factory()->create(['job_id' => $job->getKey(), 'approval_status' => JobCardApprovalStatus::Verified]);
    $unbatchedEntry = JobCardWorkEntry::factory()->create(['job_card_id' => $card->getKey()]);
    $batchedEntry = JobCardWorkEntry::factory()->create(['job_card_id' => $card->getKey()]);
    $batch = BillingBatch::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $job->client_id, 'status' => BillingBatchStatus::Prepared]);
    BillingBatchLine::query()->create(['billing_batch_id' => $batch->getKey(), 'work_entry_id' => $batchedEntry->getKey(), 'job_card_id' => $card->getKey(), 'job_id' => $job->getKey(), 'source_type' => BillingSourceType::HeavyMachineryWorkEntry->value, 'source_id' => $batchedEntry->getKey(), 'source_reference' => $card->card_number, 'activity_date' => now()->toDateString(), 'hours' => 8, 'quantity' => 8, 'billing_unit' => 'hourly', 'resolved_rate' => 1, 'currency' => 'GHS', 'line_amount' => 8]);
    BillingRecord::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $job->client_id, 'billing_batch_id' => $batch->getKey()]);
    $waybillJob = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::Trucking]);
    $waybill = Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $waybillJob->client_id, 'job_id' => $waybillJob->getKey(), 'waybill_number' => 'WB-CONTROL-1', 'waybill_date' => now()->toDateString(), 'driver_name' => 'Driver', 'truck_number' => 'TR-1', 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'number_of_trips' => 1, 'status' => WaybillStatus::Verified]);
    $batchedWaybill = Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $waybillJob->client_id, 'job_id' => $waybillJob->getKey(), 'waybill_number' => 'WB-CONTROL-2', 'waybill_date' => now()->toDateString(), 'driver_name' => 'Driver', 'truck_number' => 'TR-2', 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'number_of_trips' => 1, 'status' => WaybillStatus::Verified]);
    BillingBatchLine::query()->create(['billing_batch_id' => $batch->getKey(), 'job_id' => $waybillJob->getKey(), 'source_type' => BillingSourceType::TruckingWaybill->value, 'source_id' => $batchedWaybill->getKey(), 'source_reference' => $batchedWaybill->waybill_number, 'activity_date' => now()->toDateString(), 'quantity' => 1, 'billing_unit' => 'trip', 'resolved_rate' => 1, 'currency' => 'GHS', 'line_amount' => 1]);

    $control = app(OperationsControlService::class)->forCompany($tenant->getKey(), $company->getKey());
    expect($control['backlog']['heavy_evidence_awaiting_finance'])->toBe(1)->and($control['backlog']['waybills_awaiting_finance'])->toBe(1)->and($control['backlog']['prepared_without_record'])->toBe(0);
});
