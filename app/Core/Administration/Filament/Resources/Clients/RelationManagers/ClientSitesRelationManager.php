<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\RelationManagers;

use App\CRM\Actions\ClientSites\SetPrimaryClientSiteAction;
use App\CRM\Enums\ClientSiteStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientSitesRelationManager extends RelationManager
{
    protected static string $relationship = 'sites';

    protected static ?string $title = 'Sites';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Site profile')
                ->schema([
                    TextInput::make('site_code')->required()->maxLength(50)->placeholder('SITE-001'),
                    TextInput::make('name')->required()->maxLength(255)->placeholder('Tema Distribution Yard'),
                    Textarea::make('address_line_1')->required()->rows(3)->placeholder('Primary street address for dispatching and site visits.'),
                    TextInput::make('address_line_2')->maxLength(255)->placeholder('Building, floor, or landmark'),
                    TextInput::make('city')->maxLength(120)->placeholder('Tema'),
                    TextInput::make('region')->maxLength(120)->placeholder('Greater Accra'),
                    TextInput::make('country')->default('Ghana')->maxLength(120),
                    TextInput::make('postal_code')->maxLength(40)->placeholder('00233'),
                    TextInput::make('latitude')->numeric()->minValue(-90)->maxValue(90)->placeholder('5.6698'),
                    TextInput::make('longitude')->numeric()->minValue(-180)->maxValue(180)->placeholder('-0.0166'),
                    TextInput::make('contact_name')->maxLength(255)->placeholder('On-site coordinator'),
                    TextInput::make('contact_phone')->tel()->maxLength(30)->placeholder('+233 20 000 0000'),
                    Textarea::make('directions')->rows(3)->placeholder('Access control notes, gate contacts, or safety requirements.'),
                    Textarea::make('notes')->rows(3)->placeholder('Service windows, equipment constraints, or delivery notes.'),
                    Checkbox::make('is_primary'),
                    Select::make('status')
                        ->options(collect(ClientSiteStatus::cases())->mapWithKeys(fn (ClientSiteStatus $status) => [$status->value => $status->label()])->all())
                        ->default(ClientSiteStatus::Active->value)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Site')
                    ->searchable()
                    ->description(fn (ClientSite $record): string => $record->site_code),
                TextColumn::make('city')->placeholder('No city'),
                TextColumn::make('country'),
                TextColumn::make('contact_name')->label('Site contact')->placeholder('Not assigned')->toggleable(),
                IconColumn::make('is_primary')->boolean(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ClientSiteStatus $state): string => $state->label())
                    ->color(fn (ClientSiteStatus $state): string => $state->color()),
            ])
            ->searchPlaceholder('Search sites by name, code, or location')
            ->emptyStateHeading('No sites yet')
            ->emptyStateDescription('Add customer locations so jobs can be scheduled against the right operating site.')
            ->headerActions([
                CreateAction::make()
                    ->label('Add site')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->ownerClient()->tenant_id;
                        $data['company_id'] = $this->ownerClient()->company_id;

                        return $data;
                    })
                    ->after(function (ClientSite $record): void {
                        if ($record->is_primary) {
                            app(SetPrimaryClientSiteAction::class)->execute($record, $this->authenticatedUser());
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function (ClientSite $record): void {
                        if ($record->is_primary) {
                            app(SetPrimaryClientSiteAction::class)->execute($record, $this->authenticatedUser());
                        }
                    }),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->paginated([5, 10, 25]);
    }

    private function ownerClient(): Client
    {
        $record = $this->getOwnerRecord();

        if (! $record instanceof Client) {
            throw new \RuntimeException('Expected client owner record.');
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
}
