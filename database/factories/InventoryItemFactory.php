<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\UnitOfMeasure;
use App\Inventory\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<InventoryItem> */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'company_id' => Company::factory(),
            'code' => Str::upper(fake()->unique()->bothify('INV-###??')),
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Filters', 'Hydraulics', 'Safety', 'Consumables']),
            'unit_of_measure' => fake()->randomElement(UnitOfMeasure::cases()),
            'minimum_stock_level' => '5.000',
            'quantity_on_hand' => '0.000',
            'status' => InventoryItemStatus::Active,
        ];
    }
}
