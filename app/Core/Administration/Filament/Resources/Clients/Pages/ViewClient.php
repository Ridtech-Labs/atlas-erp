<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Pages;

use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\Core\Administration\Filament\Widgets\ClientWorkspaceWidget;
use App\CRM\Models\Client;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof Client ? $record->display_name : 'Client';
    }

    public function getSubheading(): ?string
    {
        return 'Customer account summary, relationships, and operational context.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClientWorkspaceWidget::make(['record' => $this->getRecord()]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit account'),
        ];
    }
}
