<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Tables;

use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Waybill;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WaybillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('waybill_number')->searchable(),
                TextColumn::make('job.job_number')->label('Job')->searchable(),
                TextColumn::make('waybill_date')->date()->sortable(),
                TextColumn::make('driver_name')->searchable(),
                TextColumn::make('truck_number')->searchable(),
                TextColumn::make('number_of_trips'),
                TextColumn::make('destination')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (WaybillStatus $state): string => $state->label())
                    ->color(fn (WaybillStatus $state): string => $state->color()),
            ])
            ->filters([
                SelectFilter::make('client_id')->label('Client')->relationship('client', 'legal_name')->searchable(),
                Filter::make('pickup_point')->schema([Select::make('pickup_point')->options(fn (): array => Waybill::query()->whereNotNull('pickup_point')->distinct()->orderBy('pickup_point')->pluck('pickup_point', 'pickup_point')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['pickup_point'] ?? null, fn (Builder $query, string $value): Builder => $query->where('pickup_point', $value))),
                Filter::make('destination')->schema([Select::make('destination')->options(fn (): array => Waybill::query()->whereNotNull('destination')->distinct()->orderBy('destination')->pluck('destination', 'destination')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['destination'] ?? null, fn (Builder $query, string $value): Builder => $query->where('destination', $value))),
                Filter::make('truck_number')->schema([Select::make('truck_number')->options(fn (): array => Waybill::query()->whereNotNull('truck_number')->distinct()->orderBy('truck_number')->pluck('truck_number', 'truck_number')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['truck_number'] ?? null, fn (Builder $query, string $value): Builder => $query->where('truck_number', $value))),
                Filter::make('driver_name')->schema([Select::make('driver_name')->options(fn (): array => Waybill::query()->whereNotNull('driver_name')->distinct()->orderBy('driver_name')->pluck('driver_name', 'driver_name')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['driver_name'] ?? null, fn (Builder $query, string $value): Builder => $query->where('driver_name', $value))),
                Filter::make('waybill_window')->schema([DatePicker::make('from')->label('From'), DatePicker::make('until')->label('Until')])->query(fn (Builder $query, array $data): Builder => $query->when($data['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('waybill_date', '>=', $date))->when($data['until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('waybill_date', '<=', $date))),
                SelectFilter::make('status')->options(collect(WaybillStatus::cases())->mapWithKeys(fn (WaybillStatus $status): array => [$status->value => $status->label()])->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
