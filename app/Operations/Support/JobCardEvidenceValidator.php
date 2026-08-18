<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Shared\Exceptions\BusinessException;
use App\Operations\Models\JobCard;

class JobCardEvidenceValidator
{
    public function validateForSubmission(JobCard $jobCard): void
    {
        $this->ensureHeaderEvidence($jobCard);
        $this->ensureAttachmentEvidence($jobCard);
        $this->ensureAuthoritativeWorkEntries($jobCard);
    }

    public function validateForAccountsReview(JobCard $jobCard): void
    {
        $this->ensureHeaderEvidence($jobCard);
        $this->ensureAttachmentEvidence($jobCard);
        $this->ensureAuthoritativeWorkEntries($jobCard);

        if (! $jobCard->client_endorsed || ! $jobCard->client_stamped) {
            throw new BusinessException('Client endorsement and stamp or signature must be confirmed before Accounts review.', 422);
        }
    }

    private function ensureHeaderEvidence(JobCard $jobCard): void
    {
        if (blank($jobCard->card_date)) {
            throw new BusinessException('Card date is required before this Job Card can move forward.', 422);
        }

        if (blank($jobCard->shift)) {
            throw new BusinessException('Shift is required before this Job Card can move forward.', 422);
        }

        if (blank($jobCard->equipment_reference)) {
            throw new BusinessException('Equipment context must be recorded before this Job Card can move forward.', 422);
        }

        if ($jobCard->operators()->count() === 0 && blank($jobCard->operated_by) && $jobCard->operator_id === null) {
            throw new BusinessException('Record at least one operator before this Job Card can move forward.', 422);
        }

        if (filled($jobCard->equipment_reference) && blank($jobCard->machine_number)) {
            throw new BusinessException('Machine or forklift number is required for this Job Card before it can move forward.', 422);
        }
    }

    private function ensureAttachmentEvidence(JobCard $jobCard): void
    {
        if ($jobCard->getMedia('job-card-documents')->isEmpty()) {
            throw new BusinessException('Attach the physical client Job Card evidence before this Job Card can move forward.', 422);
        }
    }

    private function ensureAuthoritativeWorkEntries(JobCard $jobCard): void
    {
        $jobCard->loadMissing('workEntries');

        if ($jobCard->workEntries->isEmpty()) {
            throw new BusinessException('Add at least one Work Entry before submitting this Job Card.', 422);
        }

        $entryTotal = round((float) $jobCard->workEntries->sum('total_hours'), 2);

        if ($entryTotal <= 0) {
            throw new BusinessException('Work Entries must produce a positive total before this Job Card can move forward.', 422);
        }

        $jobCardTotal = $jobCard->total_hours === null ? null : round((float) $jobCard->total_hours, 2);

        if ($jobCardTotal === null || abs($jobCardTotal - $entryTotal) > 0.009) {
            throw new BusinessException('Work Entry totals must reconcile exactly with the Job Card total before this Job Card can move forward.', 422);
        }
    }
}
