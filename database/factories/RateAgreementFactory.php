<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RateAgreement>
 */
class RateAgreementFactory extends Factory
{
    protected $model = RateAgreement::class;

    public function definition(): array
    {
        $tenant = Tenant::factory();
        $company = Company::factory()->for($tenant);
        $client = Client::factory()->for($tenant)->for($company);

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => $tenant,
            'company_id' => $company,
            'client_id' => $client,
            'name' => 'Heavy machinery 2026 tariff',
            'reference' => 'RA-'.fake()->unique()->numerify('#####'),
            'effective_from' => now()->startOfMonth()->toDateString(),
            'effective_to' => null,
            'status' => RateAgreementStatus::Active,
            'notes' => fake()->optional()->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}
