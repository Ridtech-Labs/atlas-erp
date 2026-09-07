<?php

declare(strict_types=1);

namespace App\Finance\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingUnit;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreementLine;
use App\Operations\Models\Waybill;
use Carbon\CarbonImmutable;

class TruckingRateResolverService
{
    /** @return array{rate_agreement_id:int,rate_agreement_line_id:int,billing_unit:string,rate:float,currency:string,pickup_point:string,destination:string} */
    public function resolveForWaybill(Waybill $waybill): array
    {
        if ($waybill->number_of_trips < 1) {
            throw new BusinessException('A Waybill must record at least one trip before commercial rate resolution.', 422);
        }
        $date = CarbonImmutable::parse((string) $waybill->waybill_date)->toDateString();
        $lines = RateAgreementLine::query()->select('rate_agreement_lines.*')->join('rate_agreements', 'rate_agreements.id', '=', 'rate_agreement_lines.rate_agreement_id')
            ->where('rate_agreements.tenant_id', $waybill->tenant_id)->where('rate_agreements.company_id', $waybill->company_id)->where('rate_agreements.client_id', $waybill->client_id)->where('rate_agreements.status', RateAgreementStatus::Active->value)->where('rate_agreement_lines.billing_unit', BillingUnit::Trip->value)
            ->whereRaw('LOWER(TRIM(rate_agreement_lines.pickup_point)) = ?', [$this->normalize((string) $waybill->pickup_point)])->whereRaw('LOWER(TRIM(rate_agreement_lines.destination)) = ?', [$this->normalize((string) $waybill->destination)])
            ->where(fn ($q) => $q->whereNull('rate_agreements.effective_from')->orWhereDate('rate_agreements.effective_from', '<=', $date))->where(fn ($q) => $q->whereNull('rate_agreements.effective_to')->orWhereDate('rate_agreements.effective_to', '>=', $date))->where(fn ($q) => $q->whereNull('rate_agreement_lines.effective_from')->orWhereDate('rate_agreement_lines.effective_from', '<=', $date))->where(fn ($q) => $q->whereNull('rate_agreement_lines.effective_to')->orWhereDate('rate_agreement_lines.effective_to', '>=', $date))->get();
        if ($lines->isEmpty()) {
            throw new BusinessException('No active per-trip rate matches this Waybill route.', 422);
        }
        if ($lines->count() > 1) {
            throw new BusinessException('Multiple active per-trip rates match this Waybill route.', 422);
        }
        $line = $lines->first();

        return ['rate_agreement_id' => (int) $line->rate_agreement_id, 'rate_agreement_line_id' => (int) $line->getKey(), 'billing_unit' => BillingUnit::Trip->value, 'rate' => (float) $line->rate, 'currency' => (string) $line->currency, 'pickup_point' => (string) $line->pickup_point, 'destination' => (string) $line->destination];
    }

    private function normalize(string $value): string
    {
        return (string) preg_replace('/\s+/', ' ', mb_strtolower(trim($value)));
    }
}
