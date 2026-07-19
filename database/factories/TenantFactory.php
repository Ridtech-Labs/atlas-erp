<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Shared\Enums\TenantStatus;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $company = fake()->company();

        return [
            'uuid' => (string) Str::uuid(),
            'name' => $company,
            'slug' => Str::slug($company).'-'.fake()->unique()->numberBetween(100, 999),
            'timezone' => 'Africa/Accra',
            'currency' => 'GHS',
            'status' => TenantStatus::Active,
        ];
    }
}
