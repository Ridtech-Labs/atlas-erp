<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Pages;

use App\Administration\Enums\PermissionName;
use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Fleet\Models\FleetAsset;
use App\Inventory\Actions\RecordStockMovementAction;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\StockMovementType;
use App\Inventory\Models\InventoryItem;
use App\Inventory\Models\StockMovement;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\Personnel;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Component;

class ViewInventoryItem extends ViewRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            $this->movementAction('openingBalance', StockMovementType::OpeningBalance, 'Set Opening Balance')
                ->visible(fn (): bool => $this->canManageMovements() && $this->openingBalanceEligible()),
            $this->movementAction('receiveStock', StockMovementType::Receipt, 'Receive Stock')
                ->color('success')
                ->visible(fn (): bool => $this->canManageMovements() && $this->activeItem()),
            $this->movementAction('issueStock', StockMovementType::Issue, 'Issue Stock')
                ->color('warning')
                ->visible(fn (): bool => $this->canManageMovements() && $this->activeItem()),
            $this->movementAction('adjustStock', StockMovementType::AdjustmentIncrease, 'Adjust Stock')
                ->color('danger')
                ->visible(fn (): bool => $this->canAdjustMovements() && $this->activeItem()),
        ];
    }

    private function movementAction(string $name, StockMovementType $type, string $label): Action
    {
        return Action::make($name)
            ->label($label)
            ->form($this->movementForm($type))
            ->action(function (array $data) use ($type): void {
                $movementType = $type;

                if ($type === StockMovementType::AdjustmentIncrease) {
                    $movementType = ($data['adjustment_direction'] ?? null) === StockMovementType::AdjustmentDecrease->value
                        ? StockMovementType::AdjustmentDecrease
                        : StockMovementType::AdjustmentIncrease;
                    unset($data['adjustment_direction']);
                }

                $movement = app(RecordStockMovementAction::class)->execute($this->inventoryItem(), $movementType, $data, $this->authenticatedUser());

                Notification::make()
                    ->success()
                    ->title('Stock movement recorded')
                    ->body(StockMovementType::from((string) $movement->getRawOriginal('movement_type'))->label().' recorded successfully.')
                    ->send();

                $this->redirect(InventoryItemResource::getUrl('view', ['record' => $this->inventoryItem()]));
            });
    }

    /** @return array<int, Component> */
    private function movementForm(StockMovementType $type): array
    {
        $item = $this->inventoryItem();
        $isAdjustment = $type === StockMovementType::AdjustmentIncrease;

        return [
            $isAdjustment
                ? Select::make('adjustment_direction')
                    ->label('Adjustment direction')
                    ->options([
                        StockMovementType::AdjustmentIncrease->value => 'Increase quantity',
                        StockMovementType::AdjustmentDecrease->value => 'Decrease quantity',
                    ])
                    ->default(StockMovementType::AdjustmentIncrease->value)
                    ->required()
                : TextInput::make('movement_type_label')->label('Movement type')->default($type->label())->disabled()->dehydrated(false),
            TextInput::make('quantity')->numeric()->step('0.001')->minValue(0.001)->required(),
            DateTimePicker::make('occurred_at')->label('Date / time')->default(now())->required(),
            TextInput::make('reference')->maxLength(255),
            Textarea::make('notes')->rows(3)->required($isAdjustment)->helperText($isAdjustment ? 'Explain why this adjustment is required.' : null),
            Select::make('fleet_asset_id')->label('Fleet asset')->options(fn (): array => FleetAsset::query()->where('company_id', $item->company_id)->orderBy('asset_number')->pluck('asset_number', 'id')->all())->searchable(),
            Select::make('job_id')->label('Job')->options(fn (): array => Job::query()->where('company_id', $item->company_id)->orderByDesc('id')->pluck('job_number', 'id')->all())->searchable(),
            Select::make('personnel_id')->label('Personnel')->options(fn (): array => Personnel::query()->where('company_id', $item->company_id)->where('status', 'active')->orderBy('first_name')->get()->mapWithKeys(fn (Personnel $person): array => [$person->getKey() => $person->full_name])->all())->searchable(),
        ];
    }

    private function inventoryItem(): InventoryItem
    {
        $record = $this->getRecord();

        if (! $record instanceof InventoryItem) {
            throw new \RuntimeException('Expected Inventory item.');
        }

        return $record;
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function activeItem(): bool
    {
        return (string) $this->inventoryItem()->getRawOriginal('status') === InventoryItemStatus::Active->value;
    }

    private function openingBalanceEligible(): bool
    {
        $item = $this->inventoryItem();

        return bccomp((string) $item->quantity_on_hand, '0', 3) === 0
            && ! StockMovement::query()->where('inventory_item_id', $item->getKey())->exists();
    }

    private function canManageMovements(): bool
    {
        return $this->authenticatedUser()->hasPermissionTo(PermissionName::StockMovementsManage->value);
    }

    private function canAdjustMovements(): bool
    {
        return $this->authenticatedUser()->hasPermissionTo(PermissionName::StockMovementsAdjust->value);
    }
}
