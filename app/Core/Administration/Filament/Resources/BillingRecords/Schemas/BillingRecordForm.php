<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BillingRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Billing Batch')
                ->description('Create one internal Billing Record from a prepared Billing Batch. Commercial values are copied from its existing snapshots.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(fn (): string => self::activeCompanyName()),
                    Select::make('billing_batch_id')
                        ->label('Prepared Billing Batch')
                        ->options(fn (): array => self::eligibleBatchOptions())
                        ->default(fn (): ?int => request()->integer('billing_batch') ?: null)
                        ->searchable()
                        ->live()
                        ->required()
                        ->disabled(fn (?BillingRecord $record): bool => $record instanceof BillingRecord)
                        ->dehydrated(fn (?BillingRecord $record): bool => ! ($record instanceof BillingRecord)),
                    Placeholder::make('client_preview')
                        ->label('Client')
                        ->content(fn (Get $get, ?BillingRecord $record): string => self::batchFor($get, $record)?->client?->display_name ?? 'Select a Billing Batch'),
                    Placeholder::make('batch_amount_preview')
                        ->label('System batch amount')
                        ->content(function (Get $get, ?BillingRecord $record): string {
                            $batch = self::batchFor($get, $record);

                            if (! $batch instanceof BillingBatch) {
                                return 'Derived when a Billing Batch is selected';
                            }

                            $currency = array_key_first($batch->subtotalAmountsByCurrency()) ?: '—';

                            return sprintf('%s %s', $currency, number_format($batch->subtotalAmount(), 2));
                        }),
                    Placeholder::make('currency_preview')
                        ->label('Currency')
                        ->content(function (Get $get, ?BillingRecord $record): string {
                            $batch = self::batchFor($get, $record);

                            return $batch instanceof BillingBatch
                                ? array_key_first($batch->subtotalAmountsByCurrency()) ?: '—'
                                : 'Derived from Billing Batch';
                        }),
                    Placeholder::make('status_preview')
                        ->label('Status')
                        ->content(fn (?BillingRecord $record): string => $record instanceof BillingRecord
                            ? $record->status->label()
                            : BillingRecordStatus::Draft->label()),
                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull()
                        ->placeholder('Internal Finance notes. VAT receipt amounts must always match the system-calculated Billing Batch amount.'),
                ])
                ->columns(2),
        ]);
    }

    /** @return array<int, string> */
    private static function eligibleBatchOptions(): array
    {
        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        if (! is_int($companyId)) {
            return [];
        }

        return BillingBatch::query()
            ->with(['client', 'lines'])
            ->where('company_id', $companyId)
            ->where('status', BillingBatchStatus::Prepared->value)
            ->whereDoesntHave('billingRecord')
            ->get()
            ->filter(fn (BillingBatch $batch): bool => $batch->lines->isNotEmpty() && ! $batch->hasMixedCurrencies())
            ->mapWithKeys(function (BillingBatch $batch): array {
                $currency = array_key_first($batch->subtotalAmountsByCurrency()) ?: '—';

                return [$batch->getKey() => sprintf('%s · %s · %s %s', $batch->batch_number, $batch->client->display_name, $currency, number_format($batch->subtotalAmount(), 2))];
            })
            ->all();
    }

    private static function activeCompanyName(): string
    {
        $user = auth()->user();
        $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

        return $company instanceof Company ? $company->name : 'No active company';
    }

    private static function batchFor(Get $get, ?BillingRecord $record): ?BillingBatch
    {
        $batchId = $get('billing_batch_id') ?? $record?->billing_batch_id;

        return is_numeric($batchId)
            ? BillingBatch::query()->with(['client', 'lines'])->find((int) $batchId)
            : null;
    }
}
