<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Core\Shared\Exceptions\BusinessException;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\UnitOfMeasure;
use Illuminate\Support\Str;

class InventoryItemData
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{code:string,name:string,category:string,unit_of_measure:UnitOfMeasure,description:?string,minimum_stock_level:string,status:InventoryItemStatus}
     */
    public static function normalize(array $data): array
    {
        $code = is_string($data['code'] ?? null) ? Str::upper(Str::squish($data['code'])) : '';
        $name = is_string($data['name'] ?? null) ? Str::squish($data['name']) : '';
        $category = is_string($data['category'] ?? null) ? Str::squish($data['category']) : '';
        $unit = is_string($data['unit_of_measure'] ?? null) ? UnitOfMeasure::tryFrom($data['unit_of_measure']) : null;
        $status = is_string($data['status'] ?? null) ? InventoryItemStatus::tryFrom($data['status']) : null;

        if ($code === '') {
            throw new BusinessException('Enter an Inventory item code.', 422);
        }

        if ($name === '') {
            throw new BusinessException('Enter an Inventory item name.', 422);
        }

        if ($category === '') {
            throw new BusinessException('Enter an Inventory category.', 422);
        }

        if (! $unit instanceof UnitOfMeasure) {
            throw new BusinessException('Select a valid unit of measure.', 422);
        }

        if (! $status instanceof InventoryItemStatus) {
            throw new BusinessException('Select a valid Inventory item status.', 422);
        }

        return [
            'code' => $code,
            'name' => $name,
            'category' => $category,
            'unit_of_measure' => $unit,
            'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
            'minimum_stock_level' => self::quantity($data['minimum_stock_level'] ?? null, 'minimum stock level'),
            'status' => $status,
        ];
    }

    private static function quantity(mixed $value, string $label): string
    {
        $quantity = is_scalar($value) ? trim((string) $value) : '';

        if (! preg_match('/^(0|[1-9]\d{0,10})(?:\.(\d{1,3}))?$/', $quantity, $matches)) {
            throw new BusinessException("Enter a valid non-negative {$label}.", 422);
        }

        return sprintf('%s.%03d', $matches[1], (int) str_pad($matches[2] ?? '', 3, '0'));
    }
}
