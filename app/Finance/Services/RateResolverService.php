<?php

declare(strict_types=1);

namespace App\Finance\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingUnit;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreementLine;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class RateResolverService
{
    /**
     * @return array{rate_agreement_id:int, rate_agreement_line_id:int, rate_agreement_name:string, rate_agreement_reference:?string, currency:string, rate:float}
     */
    public function resolveForWorkEntry(JobCardWorkEntry $workEntry): array
    {
        $jobCard = $workEntry->jobCard()->with(['client'])->firstOrFail();

        return $this->resolveForJobCard($jobCard);
    }

    /**
     * @return array{rate_agreement_id:int, rate_agreement_line_id:int, rate_agreement_name:string, rate_agreement_reference:?string, currency:string, rate:float}
     */
    public function resolveForJobCard(JobCard $jobCard): array
    {
        $activityDate = filled($jobCard->card_date)
            ? CarbonImmutable::parse((string) $jobCard->card_date)->toDateString()
            : now()->toDateString();

        $machineMatches = $this->matchingLinesQuery($jobCard, $activityDate)
            ->when(filled($jobCard->machine_number), fn (Builder $query): Builder => $query->whereRaw(
                'LOWER(TRIM(rate_agreement_lines.machine_number)) = ?',
                [$this->normalize((string) $jobCard->machine_number)],
            ))
            ->get();

        if ($machineMatches->count() > 1) {
            throw new BusinessException('Multiple machine-specific billing rates match this Job Card evidence. Resolve the ambiguity before billing.', 422);
        }

        if ($machineMatches->count() === 1) {
            /** @var RateAgreementLine $line */
            $line = $machineMatches->first();

            return $this->resolvedLinePayload($line);
        }

        if (blank($jobCard->equipment_reference)) {
            throw new BusinessException('No applicable billing rate exists for this Job Card evidence.', 422);
        }

        $classMatches = $this->matchingLinesQuery($jobCard, $activityDate)
            ->whereNull('rate_agreement_lines.machine_number')
            ->whereRaw('LOWER(TRIM(rate_agreement_lines.equipment_reference)) = ?', [$this->normalize((string) $jobCard->equipment_reference)])
            ->get();

        if ($classMatches->isEmpty()) {
            throw new BusinessException('No applicable billing rate exists for this Job Card evidence.', 422);
        }

        if ($classMatches->count() > 1) {
            throw new BusinessException('Multiple billing rates match this Job Card evidence. Resolve the ambiguity before billing.', 422);
        }

        /** @var RateAgreementLine $line */
        $line = $classMatches->first();

        return $this->resolvedLinePayload($line);
    }

    /**
     * @return Builder<RateAgreementLine>
     */
    private function matchingLinesQuery(JobCard $jobCard, string $activityDate): Builder
    {
        return RateAgreementLine::query()
            ->select([
                'rate_agreement_lines.*',
                'rate_agreements.name as rate_agreement_name',
                'rate_agreements.reference as rate_agreement_reference',
            ])
            ->join('rate_agreements', 'rate_agreements.id', '=', 'rate_agreement_lines.rate_agreement_id')
            ->where('rate_agreements.tenant_id', $jobCard->tenant_id)
            ->where('rate_agreements.company_id', $jobCard->company_id)
            ->where('rate_agreements.client_id', $jobCard->client_id)
            ->where('rate_agreements.status', RateAgreementStatus::Active->value)
            ->where('rate_agreement_lines.billing_unit', BillingUnit::Hourly->value)
            ->where(function (Builder $query) use ($activityDate): void {
                $query->whereNull('rate_agreements.effective_from')
                    ->orWhereDate('rate_agreements.effective_from', '<=', $activityDate);
            })
            ->where(function (Builder $query) use ($activityDate): void {
                $query->whereNull('rate_agreements.effective_to')
                    ->orWhereDate('rate_agreements.effective_to', '>=', $activityDate);
            })
            ->where(function (Builder $query) use ($activityDate): void {
                $query->whereNull('rate_agreement_lines.effective_from')
                    ->orWhereDate('rate_agreement_lines.effective_from', '<=', $activityDate);
            })
            ->where(function (Builder $query) use ($activityDate): void {
                $query->whereNull('rate_agreement_lines.effective_to')
                    ->orWhereDate('rate_agreement_lines.effective_to', '>=', $activityDate);
            })
            ->orderBy('rate_agreements.id')
            ->orderBy('rate_agreement_lines.id');
    }

    /**
     * @return array{rate_agreement_id:int, rate_agreement_line_id:int, rate_agreement_name:string, rate_agreement_reference:?string, currency:string, rate:float}
     */
    private function resolvedLinePayload(RateAgreementLine $line): array
    {
        return [
            'rate_agreement_id' => (int) $line->rate_agreement_id,
            'rate_agreement_line_id' => (int) $line->getKey(),
            'rate_agreement_name' => (string) $line->getAttribute('rate_agreement_name'),
            'rate_agreement_reference' => $line->getAttribute('rate_agreement_reference'),
            'currency' => (string) $line->currency,
            'rate' => round((float) $line->rate, 2),
        ];
    }

    private function normalize(string $value): string
    {
        return (string) preg_replace('/\s+/', ' ', mb_strtolower(trim($value)));
    }
}
