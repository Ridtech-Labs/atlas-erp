<?php

namespace App\Core\Shared\Media;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class ProtectedEvidenceUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $media = $this->media;

        if ($media === null) {
            return parent::getUrl();
        }

        $route = match ([$media->model_type, $media->collection_name]) {
            ['job_card', 'job-card-documents'] => ['atlas.job-cards.evidence.show', 'jobCard'],
            ['waybill', 'waybill-documents'] => ['atlas.waybills.evidence.show', 'waybill'],
            ['billing_record', 'vat-receipt'] => ['atlas.billing-records.receipts.show', 'billingRecord'],
            default => null,
        };

        if ($route === null || $this->conversion !== null) {
            return parent::getUrl();
        }

        [$name, $recordParameter] = $route;

        return route($name, [$recordParameter => $media->model_id, 'media' => $media->getKey()]);
    }
}
