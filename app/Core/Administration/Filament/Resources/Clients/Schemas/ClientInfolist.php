<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Schemas;

use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account overview')
                ->description('A business-facing snapshot of this customer account and its commercial profile.')
                ->schema([
                    TextEntry::make('legal_name')->label('Legal name'),
                    TextEntry::make('trading_name')->placeholder('Matches legal name'),
                    TextEntry::make('client_code')->label('Client code'),
                    TextEntry::make('client_type')
                        ->badge()
                        ->formatStateUsing(fn (ClientType $state): string => $state->label())
                        ->color(fn (ClientType $state): string => $state->color()),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (ClientStatus $state): string => $state->label())
                        ->color(fn (ClientStatus $state): string => $state->color()),
                    TextEntry::make('email')->placeholder('No email recorded'),
                    TextEntry::make('phone')->placeholder('No phone recorded'),
                    TextEntry::make('country'),
                    TextEntry::make('credit_limit')->money('GHS')->placeholder('No credit limit set'),
                    TextEntry::make('payment_terms_days')->label('Payment terms')->suffix(' days')->placeholder('Not specified'),
                    TextEntry::make('jobs_count')->label('Jobs linked'),
                    TextEntry::make('contacts_count')->label('Contacts'),
                    TextEntry::make('sites_count')->label('Sites'),
                ])->columns(2),
        ]);
    }
}
