<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Tenant;
use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use App\CRM\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->getKey()]);

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => $tenant->getKey(),
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
            'created_by' => $user->getKey(),
            'updated_by' => $user->getKey(),
        ];
    }
}
