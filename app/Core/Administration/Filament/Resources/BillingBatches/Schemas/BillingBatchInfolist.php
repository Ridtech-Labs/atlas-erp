<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Schemas;

use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillingBatchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Batch overview')
                ->schema([
                    TextEntry::make('batch_number'),
                    TextEntry::make('client.display_name')->label('Client'),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (BillingBatchStatus $state): string => $state->label()),
                    TextEntry::make('period_start')->date()->placeholder('Not derived yet'),
                    TextEntry::make('period_end')->date()->placeholder('Not derived yet'),
                    TextEntry::make('total_work_entries')
                        ->label('Work entries')
                        ->state(fn (BillingBatch $record): int => $record->totalWorkEntries()),
                    TextEntry::make('created_at')->since()->label('Created'),
                    TextEntry::make('prepared_at')->since()->label('Prepared')->placeholder('Not prepared'),
                    TextEntry::make('notes')->columnSpanFull()->placeholder('No notes recorded'),
                ])
                ->columns(2),
            Section::make('Commercial totals')
                ->schema([
                    TextEntry::make('subtotal_hours')
                        ->label('Subtotal hours')
                        ->state(fn (BillingBatch $record): string => number_format($record->subtotalHours(), 2)),
                    TextEntry::make('subtotal_amounts')
                        ->label('Subtotal by currency')
                        ->state(function (BillingBatch $record): string {
                            $subtotals = collect($record->subtotalAmountsByCurrency())
                                ->map(fn (float $amount, string $currency): string => sprintf('%s %s', $currency, number_format($amount, 2)));

                            if ($subtotals->isEmpty()) {
                                return 'No amounts captured';
                            }

                            return $subtotals->join(' • ');
                        }),
                    TextEntry::make('currency_rule')
                        ->label('Currency rule')
                        ->state(fn (BillingBatch $record): string => $record->hasMixedCurrencies()
                            ? 'Mixed-currency batch detected'
                            : 'Single-currency batch ready for external VAT receipt recording'),
                ])
                ->columns(2),
        ]);
    }
}
