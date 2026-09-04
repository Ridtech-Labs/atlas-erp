<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Pages;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Fleet\Services\JobCardFleetPrefillService;
use App\Models\User;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Models\Job;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJobCard extends CreateRecord
{
    protected static string $resource = JobCardResource::class;

    protected function fillForm(): void
    {
        $jobId = (int) request()->integer('job');
        $data = ['job_id' => $jobId];

        if ($jobId > 0) {
            $job = Job::query()->find($jobId);

            if ($job instanceof Job) {
                $data = [
                    ...$data,
                    ...app(JobCardFleetPrefillService::class)->forJob($job),
                ];
            }
        }

        $this->form->fill($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $jobId = (int) ($data['job_id'] ?? request()->integer('job'));
        $job = Job::query()->findOrFail($jobId);
        $attachments = $this->normalizeAttachments($data['attachments'] ?? []);
        unset($data['attachments']);

        return app(CreateJobCardAction::class)->execute($job, $data, $this->authenticatedUser(), $attachments);
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
