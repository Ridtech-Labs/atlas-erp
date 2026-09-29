<?php

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

function sensitiveMediaFixtures(): array
{
    Storage::fake('public');
    Storage::fake('private');
    config(['media-library.disk_name' => 'private']);

    $tenant = Tenant::factory()->create();
    $company = Company::factory()->for($tenant)->create();
    $client = Client::factory()->for($tenant)->for($company)->create();
    $job = Job::factory()->for($tenant)->for($company)->for($client)->create();
    $jobCard = JobCard::factory()->for($tenant)->for($company)->for($client)->for($job)->create();
    $waybill = Waybill::query()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => $job->getKey(),
        'client_id' => $client->getKey(),
        'waybill_number' => 'WB-MIGRATION-001',
        'waybill_date' => now()->toDateString(),
        'driver_name' => 'Migration Driver',
        'truck_number' => 'MIGRATION-001',
        'number_of_trips' => 1,
        'status' => WaybillStatus::Recorded->value,
    ]);

    return [$jobCard, $waybill, billingRecordForMedia($tenant, $company, $client)];
}

function billingRecordForMedia(Tenant $tenant, Company $company, Client $client): BillingRecord
{
    $batch = BillingBatch::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
    ]);

    return BillingRecord::query()->create([
        'uuid' => (string) str()->uuid(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'billing_batch_id' => $batch->getKey(),
        'record_number' => 'BR-MIGRATION-'.fake()->unique()->numerify('####'),
        'status' => 'draft',
        'batch_amount' => 100.00,
        'currency' => 'GHS',
    ]);
}

function publicMedia(HasMedia $model, string $collection, string $fileName): Media
{
    config(['media-library.disk_name' => 'public']);
    $media = $model->addMediaFromString('sensitive evidence')->usingFileName($fileName)->toMediaCollection($collection);
    config(['media-library.disk_name' => 'private']);

    return $media->fresh();
}

test('dry run reports eligible sensitive media without changing files or metadata', function () {
    [$jobCard, $waybill, $billingRecord] = sensitiveMediaFixtures();
    $media = [
        publicMedia($jobCard, 'job-card-documents', 'card.txt'),
        publicMedia($waybill, 'waybill-documents', 'waybill.txt'),
        publicMedia($billingRecord, 'vat-receipt', 'receipt.txt'),
    ];

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--dry-run' => true])
        ->assertExitCode(0);

    foreach ($media as $item) {
        expect($item->fresh()->disk)->toBe('public')
            ->and(Storage::disk('public')->exists($item->getPathRelativeToRoot()))->toBeTrue()
            ->and(Storage::disk('private')->exists($item->getPathRelativeToRoot()))->toBeFalse();
    }
});

test('command migrates all eligible media, verifies copies, and retains public sources', function () {
    [$jobCard, $waybill, $billingRecord] = sensitiveMediaFixtures();
    $media = [
        publicMedia($jobCard, 'job-card-documents', 'card.txt'),
        publicMedia($waybill, 'waybill-documents', 'waybill.txt'),
        publicMedia($billingRecord, 'vat-receipt', 'receipt.txt'),
    ];
    $directory = dirname($media[0]->getPathRelativeToRoot());
    Storage::disk('public')->put($directory.'/conversions/preview.txt', 'preview');

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--execute' => true])
        ->assertExitCode(0);

    foreach ($media as $item) {
        expect($item->fresh()->disk)->toBe('private')
            ->and($item->fresh()->conversions_disk)->toBe('private')
            ->and(Storage::disk('public')->exists($item->getPathRelativeToRoot()))->toBeTrue()
            ->and(Storage::disk('private')->exists($item->getPathRelativeToRoot()))->toBeTrue();
    }

    expect(Storage::disk('public')->exists($directory.'/conversions/preview.txt'))->toBeTrue()
        ->and(Storage::disk('private')->exists($directory.'/conversions/preview.txt'))->toBeTrue();

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--execute' => true])
        ->assertExitCode(0);

    expect($media[0]->fresh()->disk)->toBe('private');
});

test('command skips already private media and ignores unrelated public collections', function () {
    [$jobCard] = sensitiveMediaFixtures();
    $private = $jobCard->addMediaFromString('private')->usingFileName('private.txt')->toMediaCollection('job-card-documents')->fresh();
    $user = User::factory()->create();
    $unrelated = publicMedia($user, 'unrelated-profile-document', 'profile.txt');

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--execute' => true])
        ->assertExitCode(0);

    expect($private->fresh()->disk)->toBe('private')
        ->and($unrelated->fresh()->disk)->toBe('public')
        ->and(Storage::disk('public')->exists($unrelated->getPathRelativeToRoot()))->toBeTrue();
});

test('missing source and unavailable target leave public metadata unchanged and fail safely', function () {
    [$jobCard] = sensitiveMediaFixtures();
    $missing = publicMedia($jobCard, 'job-card-documents', 'missing.txt');
    Storage::disk('public')->delete($missing->getPathRelativeToRoot());

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--execute' => true])
        ->assertExitCode(1);

    expect($missing->fresh()->disk)->toBe('public');

    $failedCopy = publicMedia($jobCard, 'job-card-documents', 'failed-copy.txt');
    config(['media-library.disk_name' => 'missing-private-disk']);

    $this->artisan('atlas:migrate-sensitive-media-to-private', ['--execute' => true])
        ->assertExitCode(1);

    expect($failedCopy->fresh()->disk)->toBe('public');
});
