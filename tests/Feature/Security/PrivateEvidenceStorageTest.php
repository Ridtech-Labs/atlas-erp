<?php

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Models\User;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

function evidenceContext(TestCase $test): array
{
    app(RoleAndPermissionSeeder::class)->run();

    $tenant = Tenant::factory()->create();
    $company = Company::factory()->for($tenant)->create();
    $client = Client::factory()->for($tenant)->for($company)->create();
    $actor = User::factory()->for($tenant)->create();
    $actor->companies()->sync([$company->getKey()]);
    $actor->assignRole(RoleName::OperationsManager->value);
    $test->actingAs($actor);

    return [$tenant, $company, $client, $actor];
}

test('new job card and waybill evidence use the private media disk', function () {
    [$tenant, $company, $client] = evidenceContext($this);
    $job = Job::factory()->for($tenant)->for($company)->for($client)->create();
    $card = JobCard::factory()->for($tenant)->for($company)->for($client)->for($job)->create();
    $waybill = Waybill::query()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => $job->getKey(),
        'client_id' => $client->getKey(),
        'waybill_number' => 'WB-PRIVATE-001',
        'waybill_date' => now()->toDateString(),
        'driver_name' => 'Private Evidence Driver',
        'truck_number' => 'PRIVATE-001',
        'number_of_trips' => 1,
        'status' => WaybillStatus::Recorded->value,
    ]);

    $card->addMediaFromString('job-card')->usingFileName('card.txt')->toMediaCollection('job-card-documents');
    $waybill->addMediaFromString('waybill')->usingFileName('waybill.txt')->toMediaCollection('waybill-documents');

    expect($card->getFirstMedia('job-card-documents')?->disk)->toBe('private')
        ->and($waybill->getFirstMedia('waybill-documents')?->disk)->toBe('private');
});

test('operational evidence routes reject guests and foreign-company users', function () {
    [$tenant, $company, $client, $actor] = evidenceContext($this);
    $job = Job::factory()->for($tenant)->for($company)->for($client)->create();
    $card = JobCard::factory()->for($tenant)->for($company)->for($client)->for($job)->create();
    $card->addMediaFromString('job-card')->usingFileName('card.txt')->toMediaCollection('job-card-documents');
    $media = $card->getFirstMedia('job-card-documents');

    expect($media?->model_type)->toBe('job_card')
        ->and($media?->model_id)->toBe($card->getKey())
        ->and($media?->collection_name)->toBe('job-card-documents')
        ->and(Storage::disk('private')->exists($media?->getPathRelativeToRoot() ?? ''))->toBeTrue();
    expect($actor->can('view', $card))->toBeTrue();

    $url = route('atlas.job-cards.evidence.show', ['jobCard' => $card, 'media' => $media]);

    $this->actingAsGuest()->get($url)->assertRedirect('/login');

    $this->actingAs($actor)->withSession(['active_company_id' => $company->getKey()])->get($url)->assertOk();

    $waybill = Waybill::query()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => $job->getKey(),
        'client_id' => $client->getKey(),
        'waybill_number' => 'WB-PRIVATE-ROUTE-001',
        'waybill_date' => now()->toDateString(),
        'driver_name' => 'Private Evidence Driver',
        'truck_number' => 'PRIVATE-ROUTE-001',
        'number_of_trips' => 1,
        'status' => WaybillStatus::Recorded->value,
    ]);
    $waybill->addMediaFromString('waybill')->usingFileName('waybill.txt')->toMediaCollection('waybill-documents');
    $waybillMedia = $waybill->getFirstMedia('waybill-documents');
    $waybillUrl = route('atlas.waybills.evidence.show', ['waybill' => $waybill, 'media' => $waybillMedia]);

    $this->actingAsGuest()->get($waybillUrl)->assertRedirect('/login');
    $this->actingAs($actor)->withSession(['active_company_id' => $company->getKey()])->get($waybillUrl)->assertOk();

    $foreignTenant = Tenant::factory()->create();
    $foreignCompany = Company::factory()->for($foreignTenant)->create();
    $foreignUser = User::factory()->for($foreignTenant)->create();
    $foreignUser->companies()->sync([$foreignCompany->getKey()]);
    $foreignUser->assignRole(RoleName::OperationsManager->value);

    $this->actingAs($foreignUser)->withSession(['active_company_id' => $foreignCompany->getKey()])->get($url)->assertForbidden();
    $this->actingAs($foreignUser)->withSession(['active_company_id' => $foreignCompany->getKey()])->get($waybillUrl)->assertNotFound();
});

test('evidence routes reject media that does not belong to the requested record', function () {
    [$tenant, $company, $client, $actor] = evidenceContext($this);
    $job = Job::factory()->for($tenant)->for($company)->for($client)->create();
    $first = JobCard::factory()->for($tenant)->for($company)->for($client)->for($job)->create();
    $second = JobCard::factory()->for($tenant)->for($company)->for($client)->for($job)->create();
    $second->addMediaFromString('job-card')->usingFileName('card.txt')->toMediaCollection('job-card-documents');

    $this->actingAs($actor)->get(route('atlas.job-cards.evidence.show', [
        'jobCard' => $first,
        'media' => $second->getFirstMedia('job-card-documents'),
    ]))->assertNotFound();
});
