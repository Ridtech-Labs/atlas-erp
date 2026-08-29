<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BillingBatch>
 */
class BillingBatchFactory extends Factory
{
    protected $model = BillingBatch::class;

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
            'batch_number' => 'BB-'.fake()->unique()->numerify('#####'),
            'status' => BillingBatchStatus::Draft,
            'period_start' => null,
            'period_end' => null,
            'notes' => fake()->optional()->sentence(),
            'prepared_at' => null,
            'prepared_by' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}
