<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Tables;

use App\Operations\Enums\WaybillStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
