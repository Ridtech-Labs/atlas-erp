<?php

declare(strict_types=1);

namespace Database\Factories;

use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ClientContact>
 */
class ClientContactFactory extends Factory
{
    protected $model = ClientContact::class;

    public function definition(): array
    {
        $client = Client::factory()->create();

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => $client->tenant_id,
            'client_id' => $client->getKey(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'job_title' => fake()->optional()->jobTitle(),
            'department' => fake()->optional()->word(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'alternate_phone' => fake()->optional()->phoneNumber(),
            'is_primary' => false,
            'receives_invoices' => fake()->boolean(),
            'receives_operational_updates' => fake()->boolean(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
