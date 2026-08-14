<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Pages;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Operations\Models\JobCard;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobCard extends ViewRecord
{
    protected static string $resource = JobCardResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();
        $cardDate = $record instanceof JobCard ? $record->getAttribute('card_date') : null;

        return $record instanceof JobCard
            ? sprintf('Job Card %s', filled($cardDate) ? CarbonImmutable::parse((string) $cardDate)->format('j M Y') : $record->getKey())
            : 'Job Card';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewJob')
                ->label('View Job')
                ->url(fn (): string => JobResource::getUrl('view', ['record' => $this->currentRecord()->job_id])),
            EditAction::make()
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('approval_status') !== 'approved'),
        ];
    }

    private function currentRecord(): JobCard
    {
        if (! $this->record instanceof JobCard) {
            throw new \RuntimeException('Expected job card record.');
        }

        return $this->record;
    }
}
