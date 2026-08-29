<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Tables;

use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillingRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('record_number')->label('Record')->searchable()->sortable(),
                TextColumn::make('client.display_name')->label('Client')->searchable()->sortable(),
                TextColumn::make('billingBatch.batch_number')->label('Billing Batch')->searchable()->sortable(),
                TextColumn::make('external_receipt_reference')->label('VAT receipt ref.')->searchable()->placeholder('Not recorded'),
                TextColumn::make('batch_amount')->label('System amount')->state(fn (BillingRecord $record): string => sprintf('%s %s', $record->currency, number_format((float) $record->batch_amount, 2))),
                TextColumn::make('receipt_amount')->label('Receipt amount')->state(fn (BillingRecord $record): string => $record->receipt_amount === null ? '—' : sprintf('%s %s', $record->currency, number_format((float) $record->receipt_amount, 2))),
                TextColumn::make('reconciliation')->state(fn (BillingRecord $record): string => $record->receipt_amount === null
                    ? 'Awaiting receipt'
                    : ($record->receiptAmountMatchesBatch() ? 'Exact match' : 'Invalid mismatch')),
                TextColumn::make('status')->badge()->formatStateUsing(fn (BillingRecordStatus $state): string => $state->label()),
                TextColumn::make('issued_at')->date()->placeholder('Draft'),
                TextColumn::make('paid_at')->date()->placeholder('Unpaid'),
            ])
            ->filters([
                SelectFilter::make('status')->options(collect(BillingRecordStatus::cases())->mapWithKeys(fn (BillingRecordStatus $status): array => [$status->value => $status->label()])->all()),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Search record, VAT receipt reference, client, or Billing Batch')
            ->emptyStateHeading('No Billing Records yet')
            ->emptyStateDescription('Create a Billing Record after Finance prepares a Billing Batch and issues the external VAT receipt.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->visible(fn (BillingRecord $record): bool => $record->status === BillingRecordStatus::Draft),
            ]);
    }
}
