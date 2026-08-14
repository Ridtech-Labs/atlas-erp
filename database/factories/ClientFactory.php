<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use App\CRM\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'company_id' => null,
            'client_code' => 'CLI-'.fake()->unique()->numerify('####'),
            'legal_name' => fake()->company(),
            'trading_name' => fake()->optional()->companySuffix(),
            'client_type' => fake()->randomElement(ClientType::cases()),
            'status' => ClientStatus::Active,
            'tax_identification_number' => fake()->optional()->numerify('TIN-########'),
            'registration_number' => fake()->optional()->numerify('REG-######'),
            'email' => fake()->optional()->companyEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'alternate_phone' => fake()->optional()->phoneNumber(),
            'website' => fake()->optional()->url(),
            'billing_address' => fake()->optional()->address(),
            'physical_address' => fake()->optional()->address(),
            'city' => fake()->city(),
            'region' => fake()->randomElement(['Greater Accra', 'Ashanti', 'Western', 'Northern']),
            'country' => 'Ghana',
            'credit_limit' => fake()->optional()->randomFloat(2, 1000, 250000),
            'payment_terms_days' => fake()->optional()->randomElement([7, 14, 30, 45]),
            'notes' => fake()->optional()->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (Client $client): void {
                $this->ensureOperationalContext($client);
            })
            ->afterCreating(function (Client $client): void {
                $this->ensureOperationalContext($client, persist: true);
            });
    }

    private function ensureOperationalContext(Client $client, bool $persist = false): void
    {
        $tenant = $client->tenant_id
            ? Tenant::query()->find($client->tenant_id)
            : Tenant::factory()->create();

        if (! $tenant instanceof Tenant) {
            return;
        }

        $client->tenant_id = $tenant->getKey();

        $company = $client->company_id
            ? Company::query()->find($client->company_id)
            : null;

        if (! $company instanceof Company || $company->tenant_id !== $tenant->getKey()) {
            $company = Company::query()
                ->where('tenant_id', $tenant->getKey())
                ->first()
                ?? Company::factory()->create([
                    'tenant_id' => $tenant->getKey(),
                    'name' => $tenant->name,
                    'legal_name' => $tenant->name,
                    'is_default' => true,
                    'currency' => $tenant->currency,
                    'country' => $tenant->country,
                ]);

            $client->company_id = $company->getKey();
        }

        $author = $client->created_by
            ? User::query()->find($client->created_by)
            : null;

        if (! $author instanceof User || $author->tenant_id !== $tenant->getKey()) {
            $author = User::factory()->create([
                'tenant_id' => $tenant->getKey(),
                'email_verified_at' => Carbon::now(),
            ]);
        }

        $author->companies()->syncWithoutDetaching([$company->getKey()]);
        $client->created_by = $author->getKey();
        $client->updated_by = $client->updated_by && User::query()->whereKey($client->updated_by)->where('tenant_id', $tenant->getKey())->exists()
            ? $client->updated_by
            : $author->getKey();

        if ($persist && $client->exists) {
            $client->saveQuietly();
        }
    }
}
