<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\RelationManagers;

use App\Models\User;
use App\Operations\Actions\JobCardWorkEntries\CreateJobCardWorkEntryAction;
use App\Operations\Actions\JobCardWorkEntries\UpdateJobCardWorkEntryAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobCardWorkEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'workEntries';

    protected static ?string $title = 'Work Entries';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vessel')->placeholder('Not set'),
                TextColumn::make('work_area')->placeholder('Not set'),
                TextColumn::make('from_time'),
                TextColumn::make('to_time'),
                TextColumn::make('normal_hours'),
                TextColumn::make('overtime_hours'),
                TextColumn::make('total_hours')->label('Total'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn (): bool => (string) $this->ownerJobCard()->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value)
                    ->schema($this->entryForm())
                    ->using(fn (array $data): JobCardWorkEntry => app(CreateJobCardWorkEntryAction::class)->execute($this->ownerJobCard(), $data, $this->authenticatedUser())),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (): bool => (string) $this->ownerJobCard()->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value)
                    ->schema($this->entryForm())
                    ->using(fn (JobCardWorkEntry $record, array $data): JobCardWorkEntry => app(UpdateJobCardWorkEntryAction::class)->execute($record, $data, $this->authenticatedUser())),
                DeleteAction::make()
                    ->visible(fn (): bool => (string) $this->ownerJobCard()->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value)
                    ->requiresConfirmation(),
            ])
            ->emptyStateHeading('No work entries yet')
            ->emptyStateDescription('Add the actual vessel/work-area time rows captured on the physical job card.');
    }

    /**
     * @return array<int, Component>
     */
    private function entryForm(): array
    {
        return [
            TextInput::make('vessel')->maxLength(255)->placeholder('MV Atlantic Trader'),
            TextInput::make('work_area')->maxLength(255)->placeholder('Jubilee Terminal'),
            TextInput::make('from_time')->type('time')->required(),
            TextInput::make('to_time')->type('time')->required(),
            TextInput::make('normal_hours')->numeric()->step('0.01')->default(0),
            TextInput::make('overtime_hours')->numeric()->step('0.01')->default(0),
            TextInput::make('officer_name')->maxLength(255)->placeholder('Duty officer'),
            Textarea::make('notes')->rows(3),
        ];
    }

    private function ownerJobCard(): JobCard
    {
        $record = $this->getOwnerRecord();

        if (! $record instanceof JobCard) {
            throw new \RuntimeException('Expected job card owner record.');
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
