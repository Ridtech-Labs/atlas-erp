<?php

declare(strict_types=1);

namespace Database\Factories;

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
        $client = Client::factory()->create();

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => $client->tenant_id,
            'client_id' => $client->getKey(),
            'site_code' => 'SITE-'.fake()->unique()->numerify('####'),
            'name' => fake()->company().' Site',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'region' => fake()->randomElement(['Greater Accra', 'Ashanti', 'Western', 'Northern']),
            'country' => 'Ghana',
            'latitude' => fake()->latitude(-5, 11),
            'longitude' => fake()->longitude(-3, 2),
            'contact_name' => fake()->optional()->name(),
            'contact_phone' => fake()->optional()->phoneNumber(),
            'access_instructions' => fake()->optional()->sentence(),
            'operational_notes' => fake()->optional()->sentence(),
            'is_primary' => false,
            'is_active' => true,
        ];
    }
}
