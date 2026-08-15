<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Models\User;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Models\Job;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected static ?string $title = 'Create Job';

    protected ?string $subheading = 'Capture the work request, schedule expectations, and client context in one flow.';

    protected function handleRecordCreation(array $data): Model
    {
        $data['tenant_id'] ??= $this->authenticatedUser()->tenant_id;

        return app(CreateJobAction::class)->execute($data, $this->authenticatedUser());
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if ($record instanceof Job) {
            return JobResource::getUrl('view', ['record' => $record]);
        }

        return JobResource::getUrl('index');
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
