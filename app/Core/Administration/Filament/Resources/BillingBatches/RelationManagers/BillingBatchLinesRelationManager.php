<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\RelationManagers;

use App\Finance\Actions\BillingBatches\RemoveBillingBatchLineAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingBatchLine;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BillingBatchLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    protected static ?string $title = 'Batch lines';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('activity_date')->date()->sortable(),
                TextColumn::make('from_time')->label('From')->time('H:i')->placeholder('No start'),
                TextColumn::make('to_time')->label('To')->time('H:i')->placeholder('No end'),
                TextColumn::make('jobCard.card_number')->label('Job Card')->searchable(),
                TextColumn::make('job_reference')->label('Job')->searchable()->placeholder('No job reference'),
                TextColumn::make('vessel')->toggleable()->placeholder('No vessel'),
                TextColumn::make('work_area')->label('Work area')->toggleable()->placeholder('No work area'),
                TextColumn::make('equipment_reference')->label('Equipment')->placeholder('No equipment match'),
                TextColumn::make('machine_number')->label('Machine')->placeholder('No machine override'),
                TextColumn::make('hours')->numeric(decimalPlaces: 2)->label('Hours'),
                TextColumn::make('resolved_rate')->numeric(decimalPlaces: 2)->label('Rate / hr'),
                TextColumn::make('currency')->badge(),
                TextColumn::make('line_amount')->numeric(decimalPlaces: 2)->label('Amount'),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label('Remove')
                    ->visible(fn (): bool => (string) $this->ownerBatch()->getRawOriginal('status') === BillingBatchStatus::Draft->value)
                    ->requiresConfirmation()
                    ->action(fn (BillingBatchLine $record) => app(RemoveBillingBatchLineAction::class)->execute($record, $this->authenticatedUser())),
            ])
            ->defaultSort('activity_date')
            ->emptyStateHeading('No work entries added yet')
            ->emptyStateDescription('Add eligible Accounts-reviewed Work Entries to snapshot the authoritative billing rows into this batch.');
    }

    private function ownerBatch(): BillingBatch
    {
        $record = $this->getOwnerRecord();

        if (! $record instanceof BillingBatch) {
            throw new \RuntimeException('Expected billing batch owner record.');
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
