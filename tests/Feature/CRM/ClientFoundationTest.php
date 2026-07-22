<?php

use App\Administration\Enums\RoleName;
use App\CRM\Actions\ClientContacts\SetPrimaryClientContactAction;
use App\CRM\Actions\Clients\CreateClientAction;
use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use App\CRM\Models\ClientSite;
use Illuminate\Support\Facades\Gate;

test('client creation is tenant scoped and auto-generates a code', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    $client = app(CreateClientAction::class)->execute([
        'legal_name' => 'Kadmay Global Limited',
        'client_type' => 'corporate',
        'status' => 'active',
        'country' => 'Ghana',
    ], $actor);

    expect($client->tenant_id)->toBe($tenant->getKey())
        ->and($client->client_code)->toStartWith('CLI-');
});

test('tenant scoped client code uniqueness is enforced', function () {
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();

    Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_code' => 'CLI-00001',
    ]);

    Client::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'client_code' => 'CLI-00001',
    ]);

    expect(
        Client::query()->withoutGlobalScopes()->where('client_code', 'CLI-00001')->count(),
    )->toBe(2);
});

test('tenant a cannot view tenant b clients contacts or sites', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant();
    $tenantB = $this->tenant();
    $userA = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);

    $clientB = Client::factory()->create(['tenant_id' => $tenantB->getKey()]);
    $contactB = ClientContact::factory()->create([
        'tenant_id' => $tenantB->getKey(),
        'client_id' => $clientB->getKey(),
    ]);
    $siteB = ClientSite::factory()->create([
        'tenant_id' => $tenantB->getKey(),
        'client_id' => $clientB->getKey(),
    ]);

    expect(Gate::forUser($userA)->allows('view', $clientB))->toBeFalse()
        ->and(Gate::forUser($userA)->allows('view', $contactB))->toBeFalse()
        ->and(Gate::forUser($userA)->allows('view', $siteB))->toBeFalse();
});

test('only one primary contact per client is maintained transactionally', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey()]);
    $first = ClientContact::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'is_primary' => true,
    ]);
    $second = ClientContact::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'is_primary' => false,
    ]);

    app(SetPrimaryClientContactAction::class)->execute($second, $actor);

    expect($second->refresh()->is_primary)->toBeTrue()
        ->and($first->refresh()->is_primary)->toBeFalse();
});
