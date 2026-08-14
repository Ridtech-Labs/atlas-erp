<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\RelationManagers;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Models\User;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Support\OperatorAssignmentService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobCardsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobCards';

    protected static ?string $title = 'Job Cards';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('card_date')->date()->sortable(),
                TextColumn::make('shift'),
                TextColumn::make('equipment_reference')->label('Equipment')->placeholder('Not recorded'),
                TextColumn::make('operator_id')
                    ->label('Operator')
                    ->formatStateUsing(fn (mixed $state, JobCard $record): string => $record->operatorDisplayName() ?? 'Not assigned'),
                TextColumn::make('approval_status')
                    ->badge()
                    ->formatStateUsing(fn (JobCardApprovalStatus $state): string => $state->label())
                    ->color(fn (JobCardApprovalStatus $state): string => $state->color()),
                TextColumn::make('work_entries_count')->counts('workEntries')->label('Entries'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New job card')
                    ->visible(fn (): bool => (string) $this->ownerJob()->getRawOriginal('status') === JobStatus::InProgress->value)
                    ->form([
                        DatePicker::make('card_date')->required(),
                        Select::make('shift')
                            ->options(collect(JobShift::cases())->mapWithKeys(fn (JobShift $shift) => [$shift->value => $shift->label()])->all())
                            ->default(JobShift::Night->value)
                            ->required(),
                        TextInput::make('equipment_reference')->maxLength(255)->placeholder('Forklift FL-12'),
                        Select::make('operator_source')
                            ->label('Operator type')
                            ->options([
                                'company_personnel' => 'Company personnel',
                                'external' => 'External / temporary operator',
                            ])
                            ->placeholder('Select operator type')
                            ->live()
                            ->dehydrated(false)
                            ->default(fn (): ?string => $this->defaultOperatorAssignment()['source'])
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                if ($state === 'company_personnel') {
                                    $set('operated_by', null);

                                    return;
                                }

                                if ($state === 'external') {
                                    $set('operator_id', null);

                                    return;
                                }

                                $set('operator_id', null);
                                $set('operated_by', null);
                            }),
                        Select::make('operator_id')
                            ->label('Operator')
                            ->searchable()
                            ->visible(fn (callable $get): bool => $get('operator_source') === 'company_personnel')
                            ->default(fn (): ?int => $this->defaultOperatorAssignment()['operator_id'])
                            ->options(fn () => $this->companyOperators()),
                        TextInput::make('operated_by')
                            ->label('External or temporary operator name')
                            ->visible(fn (callable $get): bool => $get('operator_source') === 'external')
                            ->default(fn (): ?string => $this->defaultOperatorAssignment()['external_name'])
                            ->maxLength(255)
                            ->placeholder('Kofi Asante'),
                        TextInput::make('supervising_officer_name')->maxLength(255)->placeholder('Shift officer'),
                        Textarea::make('officer_remarks')->rows(3),
                        FileUpload::make('attachments')
                            ->multiple()
                            ->disk('local')
                            ->directory('job-card-uploads')
                            ->preserveFilenames(),
                    ])
                    ->using(function (array $data): JobCard {
                        $attachments = $this->normalizeAttachments($data['attachments'] ?? []);

                        unset($data['attachments']);

                        return app(CreateJobCardAction::class)->execute(
                            $this->ownerJob(),
                            $data,
                            $this->authenticatedUser(),
                            $attachments,
                        );
                    }),
                Action::make('openFullJobCards')
                    ->label('Browse all cards')
                    ->url(fn (): string => JobCardResource::getUrl('index', ['job' => $this->ownerJob()->getKey()])),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->url(fn (JobCard $record): string => JobCardResource::getUrl('view', ['record' => $record])),
                Action::make('edit')
                    ->label('Edit')
                    ->visible(fn (JobCard $record): bool => (string) $record->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value)
                    ->url(fn (JobCard $record): string => JobCardResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No job cards yet')
            ->emptyStateDescription('Start the job to create the first shift card automatically, then add more cards as new operational periods occur.');
    }

    /**
     * @return array<int, string>
     */
    private function companyOperators(): array
    {
        $job = $this->ownerJob();

        if (! is_int($job->company_id)) {
            return [];
        }

        return app(OperatorAssignmentService::class)->companyOperatorOptions($job->tenant_id, $job->company_id);
    }

    /**
     * @return list<string>
     */
    private function normalizeAttachments(mixed $attachments): array
    {
        if (! is_array($attachments)) {
            return [];
        }

        return array_values(array_filter($attachments, static fn (mixed $path): bool => is_string($path) && $path !== ''));
    }

    private function ownerJob(): Job
    {
        $record = $this->getOwnerRecord();

        if (! $record instanceof Job) {
            throw new \RuntimeException('Expected job owner record.');
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

    /**
     * @return array{operator_id:?int, external_name:?string, source:?string}
     */
    private function defaultOperatorAssignment(): array
    {
        return app(OperatorAssignmentService::class)->defaultJobCardAssignment($this->ownerJob());
    }
}
