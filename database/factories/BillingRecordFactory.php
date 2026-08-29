<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BillingRecord>
 */
class BillingRecordFactory extends Factory
{
    protected $model = BillingRecord::class;

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
            'billing_batch_id' => BillingBatch::factory()->for($tenant)->for($company)->for($client),
            'record_number' => 'BR-'.fake()->unique()->numerify('#####'),
            'status' => BillingRecordStatus::Draft,
            'batch_amount' => 6800.00,
            'receipt_amount' => null,
            'currency' => 'GHS',
            'external_receipt_reference' => null,
            'issued_at' => null,
            'paid_at' => null,
            'payment_reference' => null,
            'notes' => null,
            'created_by' => null,
            'updated_by' => null,
            'issued_by' => null,
            'paid_by' => null,
            'closed_by' => null,
        ];
    }
}
