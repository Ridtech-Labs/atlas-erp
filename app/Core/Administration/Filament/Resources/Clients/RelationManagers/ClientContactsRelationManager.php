<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\RelationManagers;

use App\CRM\Actions\ClientContacts\SetPrimaryClientContactAction;
use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $title = 'Contacts';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Contact details')
                ->schema([
                    TextInput::make('first_name')->required()->maxLength(255)->placeholder('Ama'),
                    TextInput::make('last_name')->required()->maxLength(255)->placeholder('Mensah'),
                    TextInput::make('job_title')->maxLength(255)->placeholder('Operations Lead'),
                    TextInput::make('department')->maxLength(255)->placeholder('Operations'),
                    TextInput::make('email')->email()->maxLength(255)->placeholder('ama@client.com'),
                    TextInput::make('phone')->tel()->maxLength(30)->placeholder('+233 20 000 0000'),
                    TextInput::make('alternate_phone')->tel()->maxLength(30)->placeholder('+233 24 000 0000'),
                    TextInput::make('notes')->maxLength(65535)->columnSpanFull()->placeholder('Preferred contact windows, invoice routing, or escalation notes.'),
                    Checkbox::make('is_primary'),
                    Checkbox::make('receives_invoices'),
                    Checkbox::make('receives_operational_updates')->default(true),
                ])
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Contact')
                    ->searchable(['first_name', 'last_name'])
                    ->description(fn (ClientContact $record): string => $record->job_title ?: 'No role recorded'),
                TextColumn::make('department')->toggleable(),
                TextColumn::make('email')->searchable()->placeholder('No email'),
                TextColumn::make('phone')->searchable()->placeholder('No phone'),
                IconColumn::make('is_primary')->boolean(),
            ])
            ->searchPlaceholder('Search contacts by name, email, or phone')
            ->emptyStateHeading('No contacts yet')
            ->emptyStateDescription('Add the people your team needs for approvals, invoices, and site coordination.')
            ->headerActions([
                CreateAction::make()
                    ->label('Add contact')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->ownerClient()->tenant_id;
                        $data['company_id'] = $this->ownerClient()->company_id;

                        return $data;
                    })
                    ->after(function (ClientContact $record): void {
                        if ($record->is_primary) {
                            app(SetPrimaryClientContactAction::class)->execute($record, $this->authenticatedUser());
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function (ClientContact $record): void {
                        if ($record->is_primary) {
                            app(SetPrimaryClientContactAction::class)->execute($record, $this->authenticatedUser());
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
