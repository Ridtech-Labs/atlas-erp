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
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => null,
            'client_id' => Client::factory(),
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

    public function configure(): static
    {
        return $this
            ->afterMaking(function (ClientContact $contact): void {
                $client = Client::query()->find($contact->client_id);

                if ($client instanceof Client) {
                    $contact->tenant_id = $client->tenant_id;
                    $contact->company_id = $client->company_id;
                }
            })
            ->afterCreating(function (ClientContact $contact): void {
                $client = Client::query()->find($contact->client_id);

                if ($client instanceof Client && ($contact->tenant_id !== $client->tenant_id || $contact->company_id !== $client->company_id)) {
                    $contact->tenant_id = $client->tenant_id;
                    $contact->company_id = $client->company_id;
                    $contact->saveQuietly();
                }
            });
    }
}
