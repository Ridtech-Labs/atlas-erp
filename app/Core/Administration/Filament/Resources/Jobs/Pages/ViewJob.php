<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Widgets\JobWorkspaceWidget;
use App\Operations\Models\Job;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof Job ? $record->job_number : 'Job';
    }

    public function getSubheading(): ?string
    {
        return 'Operational summary, commercial context, and workflow progress.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JobWorkspaceWidget::make(['record' => $this->getRecord()]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit job'),
        ];
    }
}
