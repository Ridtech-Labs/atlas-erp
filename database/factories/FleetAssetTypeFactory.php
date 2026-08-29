<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Models\FleetAssetType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<FleetAssetType> */
class FleetAssetTypeFactory extends Factory
{
    protected $model = FleetAssetType::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'company_id' => Company::factory(),
            'name' => fake()->unique()->randomElement(['Reach Stacker', 'Forklift 4.5T/5T', 'Terminal Tractor']),
            'category' => fake()->randomElement(FleetAssetCategory::cases()),
            'is_active' => true,
        ];
    }
}
