<?php

declare(strict_types=1);

namespace Database\Factories;

use App\CRM\Enums\ClientSiteStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ClientSite>
 */
class ClientSiteFactory extends Factory
{
    protected $model = ClientSite::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => null,
            'company_id' => null,
            'client_id' => Client::factory(),
            'site_code' => 'SITE-'.fake()->unique()->numerify('####'),
            'name' => fake()->company().' Site',
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->bothify('Suite ##'),
            'city' => fake()->city(),
            'region' => fake()->randomElement(['Greater Accra', 'Ashanti', 'Western', 'Northern']),
            'country' => 'Ghana',
            'postal_code' => fake()->optional()->postcode(),
            'latitude' => fake()->latitude(-5, 11),
            'longitude' => fake()->longitude(-3, 2),
            'contact_name' => fake()->optional()->name(),
            'contact_phone' => fake()->optional()->phoneNumber(),
            'directions' => fake()->optional()->sentence(),
            'notes' => fake()->optional()->sentence(),
            'is_primary' => false,
            'status' => ClientSiteStatus::Active,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (ClientSite $site): void {
                $client = Client::query()->find($site->client_id);

                if ($client instanceof Client) {
                    $site->tenant_id = $client->tenant_id;
                    $site->company_id = $client->company_id;
                }
            })
            ->afterCreating(function (ClientSite $site): void {
                $client = Client::query()->find($site->client_id);

                if ($client instanceof Client && ($site->tenant_id !== $client->tenant_id || $site->company_id !== $client->company_id)) {
                    $site->tenant_id = $client->tenant_id;
                    $site->company_id = $client->company_id;
                    $site->saveQuietly();
                }
            });
    }
}
