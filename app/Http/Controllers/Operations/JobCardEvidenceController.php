<?php

declare(strict_types=1);

namespace App\Http\Controllers\Operations;

use App\Operations\Models\JobCard;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JobCardEvidenceController
{
    public function __invoke(Request $request, JobCard $jobCard, Media $media): BinaryFileResponse
    {
        abort_unless((bool) $request->user()?->can('view', $jobCard), 403);
        abort_unless(
            $media->model_type === 'job_card'
            && (int) $media->model_id === (int) $jobCard->getKey()
            && $media->collection_name === 'job-card-documents',
            404,
        );

        return $request->boolean('download')
            ? response()->download($media->getPath(), $media->file_name)
            : response()->file($media->getPath(), ['Content-Type' => $media->mime_type]);
    }
}
