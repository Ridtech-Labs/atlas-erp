<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Activities\Pages;

use App\Core\Administration\Filament\Resources\Activities\ActivityResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to activity log')
                ->url(static::getResource()::getUrl('index')),
        ];
    }
}
