<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\FleetAssetType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<FleetAsset> */
class FleetAssetFactory extends Factory
{
    protected $model = FleetAsset::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'company_id' => Company::factory(),
            'fleet_asset_type_id' => FleetAssetType::factory(),
            'asset_number' => 'AST-'.fake()->unique()->numerify('####'),
            'registration_number' => null,
            'make' => fake()->optional()->company(),
            'model' => fake()->optional()->bothify('Model ##'),
            'serial_number' => fake()->optional()->bothify('SN-########'),
            'operational_status' => FleetAssetOperationalStatus::Available,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
