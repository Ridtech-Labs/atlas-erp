<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Models\User;
use App\Operations\Actions\Waybills\CreateWaybillAction;
use App\Operations\Models\Job;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWaybill extends CreateRecord
{
    protected static string $resource = WaybillResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $job = Job::query()->findOrFail((int) ($data['job_id'] ?? request()->integer('job')));
        $user = $this->authenticatedUser();
        $attachments = $this->normalizeAttachments($data['attachments'] ?? []);

        unset($data['attachments']);

        return app(CreateWaybillAction::class)->execute($job, $data, $user, $attachments);
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

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
