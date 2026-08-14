<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'name' => $name,
            'legal_name' => $name,
            'code' => substr(strtoupper(Str::slug($name, '')).fake()->unique()->bothify('??###'), 0, 12),
            'status' => 'active',
            'currency' => 'GHS',
            'country' => 'Ghana',
            'is_default' => false,
        ];
    }
}
