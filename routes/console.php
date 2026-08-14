<?php

use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('atlas:audit-operator-assignments', function () {
    $suspectJobs = Job::query()
        ->whereNotNull('created_by')
        ->whereNull('assigned_operator_name')
        ->whereColumn('assigned_operator_id', 'created_by')
        ->orderBy('id')
        ->get(['id', 'job_number', 'company_id', 'assigned_operator_id', 'created_by']);

    $suspectJobCards = JobCard::query()
        ->whereNotNull('created_by')
        ->whereNull('operated_by')
        ->whereColumn('operator_id', 'created_by')
        ->orderBy('id')
        ->get(['id', 'card_number', 'job_id', 'company_id', 'operator_id', 'created_by']);

    $this->info(sprintf('Suspect Jobs: %d', $suspectJobs->count()));

    if ($suspectJobs->isNotEmpty()) {
        $this->table(
            ['ID', 'Job', 'Company', 'Operator ID', 'Created By'],
            $suspectJobs->map(fn (Job $job): array => [
                $job->getKey(),
                $job->job_number,
                $job->company_id,
                $job->assigned_operator_id,
                $job->created_by,
            ])->all(),
        );
    }

    $this->newLine();
    $this->info(sprintf('Suspect Job Cards: %d', $suspectJobCards->count()));

    if ($suspectJobCards->isNotEmpty()) {
        $this->table(
            ['ID', 'Card', 'Job ID', 'Company', 'Operator ID', 'Created By'],
            $suspectJobCards->map(fn (JobCard $card): array => [
                $card->getKey(),
                $card->card_number,
                $card->job_id,
                $card->company_id,
                $card->operator_id,
                $card->created_by,
            ])->all(),
        );
    }
})->purpose('List Jobs and Job Cards that may have inherited the creator as operator');
