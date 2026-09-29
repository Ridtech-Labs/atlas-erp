<?php

declare(strict_types=1);

namespace App\Http\Controllers\Finance;

use App\Finance\Models\BillingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BillingRecordReceiptController
{
    public function __invoke(Request $request, BillingRecord $billingRecord, Media $media): BinaryFileResponse
    {
        abort_unless((bool) $request->user()?->can('view', $billingRecord), 403);
        abort_unless(
            $media->model_type === 'billing_record'
            && (int) $media->model_id === (int) $billingRecord->getKey()
            && $media->collection_name === 'vat-receipt',
            404,
        );

        abort_unless(Storage::disk($media->disk)->exists($media->getPathRelativeToRoot()), 404);

        return $request->boolean('download')
            ? response()->download($media->getPath(), $media->file_name)
            : response()->file($media->getPath());
    }
}
