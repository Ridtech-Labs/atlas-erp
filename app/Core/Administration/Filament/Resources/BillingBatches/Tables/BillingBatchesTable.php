<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Tables;

use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillingBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.display_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BillingBatchStatus $state): string => $state->label()),
                TextColumn::make('period_start')->date()->placeholder('Pending'),
                TextColumn::make('period_end')->date()->placeholder('Pending'),
                TextColumn::make('lines_count')->label('Work Entries')->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
                TextColumn::make('prepared_at')
                    ->label('Prepared')
                    ->since()
                    ->placeholder('Draft'),
                TextColumn::make('subtotal_amount')
                    ->label('Subtotal')
                    ->state(function (BillingBatch $record): string {
                        $subtotals = $record->subtotalAmountsByCurrency();

                        if ($subtotals === []) {
                            return 'No amounts';
                        }

                        if (count($subtotals) > 1) {
                            return collect($subtotals)
                                ->map(fn (float $amount, string $currency): string => sprintf('%s %s', $currency, number_format($amount, 2)))
                                ->join(' • ');
                        }

                        $currency = array_key_first($subtotals) ?: 'GHS';

                        return sprintf('%s %s', $currency, number_format($record->subtotalAmount(), 2));
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(BillingBatchStatus::cases())->mapWithKeys(fn (BillingBatchStatus $status) => [$status->value => $status->label()])->all()),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Search billing batches by number or client')
            ->emptyStateHeading('No billing batches yet')
            ->emptyStateDescription('Create a billing batch to group reviewed operational evidence for a client billing cycle.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (BillingBatch $record): bool => (string) $record->getRawOriginal('status') === BillingBatchStatus::Draft->value),
            ]);
    }
}
