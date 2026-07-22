<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected static ?string $title = 'Jobs';

    protected ?string $subheading = 'Track operational work from request through approval, scheduling, and completion.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New job'),
        ];
    }
}
