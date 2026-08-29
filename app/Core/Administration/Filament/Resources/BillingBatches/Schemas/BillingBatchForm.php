<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillingBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Batch scope')
                ->description('A Billing Batch groups Accounts-reviewed operational evidence for one client inside the active company workspace.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    Select::make('client_id')
                        ->label('Client')
                        ->options(fn (): array => self::clientOptions())
                        ->searchable()
                        ->required()
                        ->disabled(fn (?BillingBatch $record): bool => $record instanceof BillingBatch)
                        ->dehydrated(fn (?BillingBatch $record): bool => ! ($record instanceof BillingBatch)),
                    Placeholder::make('status_preview')
                        ->label('Initial status')
                        ->content(fn (?BillingBatch $record): string => $record instanceof BillingBatch
                            ? BillingBatchStatus::from((string) $record->getRawOriginal('status'))->label()
                            : BillingBatchStatus::Draft->label()),
                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull()
                        ->placeholder('Commercial review notes, cycle reference, or internal batching context.'),
                ])
                ->columns(2),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private static function clientOptions(): array
    {
        $user = auth()->user();

        if ($user === null) {
            return [];
        }

        $companyId = app(AdministrationAccessService::class)->activeCompanyId($user);

        if (! is_int($companyId)) {
            return [];
        }

        return Client::query()
            ->where('company_id', $companyId)
            ->orderBy('legal_name')
            ->get()
            ->mapWithKeys(fn (Client $client): array => [$client->getKey() => $client->display_name])
            ->all();
    }
}
