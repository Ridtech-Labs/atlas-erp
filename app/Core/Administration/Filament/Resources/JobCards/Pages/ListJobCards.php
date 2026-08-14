<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Pages;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Operations\Models\JobCard;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListJobCards extends ListRecords
{
    protected static string $resource = JobCardResource::class;

    /**
     * @return Builder<JobCard>
     */
    protected function getTableQuery(): Builder
    {
        $query = JobCard::query()
            ->with(['job', 'operator', 'approver'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
        $jobId = request()->integer('job');

        return $jobId > 0 ? $query->where('job_id', $jobId) : $query;
    }
}
