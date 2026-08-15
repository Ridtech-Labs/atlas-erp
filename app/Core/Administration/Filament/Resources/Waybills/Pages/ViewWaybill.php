<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Operations\Models\Waybill;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWaybill extends ViewRecord
{
    protected static string $resource = WaybillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') !== 'billing_ready'),
        ];
    }

    private function currentRecord(): Waybill
    {
        if (! $this->record instanceof Waybill) {
            throw new \RuntimeException('Expected waybill record.');
        }

        return $this->record;
    }
}
