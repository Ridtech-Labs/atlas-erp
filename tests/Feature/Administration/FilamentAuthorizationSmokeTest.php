<?php

use App\Administration\Enums\RoleName;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Operations\Models\Job;

test('filament administration pages render without authorization recursion', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $tenantAdmin = $this->actingAsCompanyAdministrator($tenant);
    $tenantAdmin->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $tenantId = auth()->user()?->tenant_id;
    $client = Client::factory()->create([
        'tenant_id' => $tenantId,
        'company_id' => $company->getKey(),
    ]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenantId,
        'client_id' => $client->getKey(),
    ]);
    $job = Job::factory()->create([
        'tenant_id' => $tenantId,
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
    ]);

    $this->get('/admin')->assertOk();
    $this->get('/admin/clients')->assertOk();
    $this->get('/admin/jobs')->assertOk();
    $this->get('/admin/jobs/create')->assertOk();
    $this->get("/admin/jobs/{$job->getKey()}/edit")->assertOk();
    $this->get('/admin/settings')->assertOk();

    $platformUser = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);
    $this->actingAs($platformUser);
    session()->forget('active_company_id');

    $this->get('/admin/roles')->assertOk();
    $this->get('/admin/system-health')->assertOk();
});
