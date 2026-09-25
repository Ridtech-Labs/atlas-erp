<?php

declare(strict_types=1);

namespace App\Http\Controllers\Operations;

use App\Operations\Models\Waybill;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WaybillEvidenceController
{
    public function __invoke(Request $request, Waybill $waybill, Media $media): BinaryFileResponse
    {
        abort_unless((bool) $request->user()?->can('view', $waybill), 403);
        abort_unless(
            $media->model_type === 'waybill'
            && (int) $media->model_id === (int) $waybill->getKey()
            && $media->collection_name === 'waybill-documents',
            404,
        );

        return $request->boolean('download')
            ? response()->download($media->getPath(), $media->file_name)
            : response()->file($media->getPath(), ['Content-Type' => $media->mime_type]);
    }
}
