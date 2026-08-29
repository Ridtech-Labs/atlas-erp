<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Schemas;

use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillingRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Billing Batch / System Amount')
                ->schema([
                    TextEntry::make('record_number')->label('Billing Record'),
                    TextEntry::make('billingBatch.batch_number')->label('Billing Batch'),
                    TextEntry::make('client.display_name')->label('Client'),
                    TextEntry::make('status')->badge()->formatStateUsing(fn (BillingRecordStatus $state): string => $state->label()),
                    TextEntry::make('batch_amount')->label('System calculated amount')->state(fn (BillingRecord $record): string => sprintf('%s %s', $record->currency, number_format((float) $record->batch_amount, 2))),
                    TextEntry::make('currency'),
                ])
                ->columns(2),
            Section::make('External VAT Receipt')
                ->schema([
                    TextEntry::make('external_receipt_reference')->label('VAT receipt reference')->placeholder('Not recorded'),
                    TextEntry::make('issued_at')->label('Issue date')->date()->placeholder('Not recorded'),
                    TextEntry::make('receipt_amount')->label('VAT receipt amount')->state(fn (BillingRecord $record): string => $record->receipt_amount === null ? 'Not recorded' : sprintf('%s %s', $record->currency, number_format((float) $record->receipt_amount, 2))),
                    TextEntry::make('receipt_documents')->label('VAT receipt documents')->state(fn (BillingRecord $record): string => sprintf('%d attachment(s)', $record->getMedia('vat-receipt')->count())),
                ])
                ->columns(2),
            Section::make('Reconciliation')
                ->schema([
                    TextEntry::make('amount_match')
                        ->label('Receipt amount')
                        ->state(fn (BillingRecord $record): string => $record->receipt_amount === null
                            ? 'Awaiting VAT receipt'
                            : ($record->receiptAmountMatchesBatch() ? 'Exact match' : 'Invalid amount mismatch')),
                    TextEntry::make('notes')->label('Finance notes')->placeholder('No Finance notes recorded')->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Payment')
                ->schema([
                    TextEntry::make('paid_at')->label('Payment date')->date()->placeholder('Unpaid'),
                    TextEntry::make('payment_reference')->placeholder('Not recorded'),
                ])
                ->columns(2),
            Section::make('Audit metadata')
                ->schema([
                    TextEntry::make('creator.full_name')->label('Created by')->placeholder('System'),
                    TextEntry::make('issuer.full_name')->label('Issued by')->placeholder('Not issued'),
                    TextEntry::make('paidBy.full_name')->label('Paid recorded by')->placeholder('Not paid'),
                    TextEntry::make('closedBy.full_name')->label('Closed by')->placeholder('Not closed'),
                    TextEntry::make('created_at')->dateTime()->label('Created'),
                    TextEntry::make('updated_at')->dateTime()->label('Last updated'),
                ])
                ->columns(2),
        ]);
    }
}
