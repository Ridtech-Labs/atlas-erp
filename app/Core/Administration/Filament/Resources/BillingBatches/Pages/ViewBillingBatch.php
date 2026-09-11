<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Pages;

use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Actions\BillingBatches\AddJobCardsToBillingBatchAction;
use App\Finance\Actions\BillingBatches\AddWaybillsToBillingBatchAction;
use App\Finance\Actions\BillingBatches\PrepareBillingBatchAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Finance\Services\BillingBatchEligibilityService;
use App\Finance\Services\RateResolverService;
use App\Finance\Services\TruckingRateResolverService;
use App\Models\User;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBillingBatch extends ViewRecord
{
    protected static string $resource = BillingBatchResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof BillingBatch ? $record->batch_number : 'Billing Batch';
    }

    public function getSubheading(): ?string
    {
        return 'Snapshot reviewed operational evidence, resolved rates, and commercial totals before external VAT receipt recording begins.';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Draft->value),
            Action::make('addReviewedEvidence')
                ->label('Add reviewed Work Entries')
                ->form([
                    Select::make('work_entry_ids')
                        ->label('Eligible Work Entries')
                        ->options(fn (): array => $this->eligibleWorkEntryOptions())
                        ->multiple()
                        ->searchable()
                        ->required(),
                ])
                ->action(fn (array $data) => $this->addReviewedEvidence($data))
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Draft->value),
            Action::make('addVerifiedWaybills')
                ->label('Add verified Waybills')
                ->form([Select::make('waybill_ids')->label('Eligible Trucking Waybills')->options(fn (): array => $this->eligibleWaybillOptions())->multiple()->searchable()->required()])
                ->action(fn (array $data) => $this->addVerifiedWaybills($data))
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Draft->value),
            Action::make('prepareBatch')
                ->label('Prepare Billing Batch')
                ->requiresConfirmation()
                ->action(fn () => app(PrepareBillingBatchAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Draft->value && $this->currentRecord()->lines()->exists()),
            Action::make('createBillingRecord')
                ->label('Create Billing Record')
                ->url(fn (): string => BillingRecordResource::getUrl('create', ['billing_batch' => $this->currentRecord()->getKey()]))
                ->visible(fn (): bool => (string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Prepared->value
                    && ! $this->currentRecord()->billingRecord()->exists()
                    && auth()->user()?->can('create', BillingRecord::class) === true),
        ];
    }

    /**
     * @param  array{work_entry_ids?: array<int, int|string>}  $data
     */
    public function addReviewedEvidence(array $data): void
    {
        try {
            app(AddJobCardsToBillingBatchAction::class)->execute(
                $this->currentRecord(),
                array_values(array_map('intval', $data['work_entry_ids'] ?? [])),
                $this->authenticatedUser(),
            );
        } catch (BusinessException $exception) {
            Notification::make()
                ->danger()
                ->title('Work Entry could not be added')
                ->body($exception->getMessage())
                ->send();
        }
    }

    /** @param array{waybill_ids?: array<int, int|string>} $data */
    public function addVerifiedWaybills(array $data): void
    {
        try {
            app(AddWaybillsToBillingBatchAction::class)->execute($this->currentRecord(), array_values(array_map('intval', $data['waybill_ids'] ?? [])), $this->authenticatedUser());
        } catch (BusinessException $exception) {
            Notification::make()->danger()->title('Waybill could not be added')->body($exception->getMessage())->send();
        }
    }

    /** @return array<int, string> */
    private function eligibleWaybillOptions(): array
    {
        $resolver = app(TruckingRateResolverService::class);

        return app(BillingBatchEligibilityService::class)->eligibleWaybills($this->currentRecord())->mapWithKeys(function (Waybill $waybill) use ($resolver): array {
            try {
                $rate = $resolver->resolveForWaybill($waybill);
                $amount = (float) $waybill->number_of_trips * (float) $rate['rate'];

                return [$waybill->getKey() => sprintf('%s · %s → %s · %d trips · %s %s/trip = %s %s', $waybill->waybill_number, $waybill->pickup_point, $waybill->destination, $waybill->number_of_trips, $rate['currency'], number_format((float) $rate['rate'], 2), $rate['currency'], number_format($amount, 2))];
            } catch (BusinessException $exception) {
                return [$waybill->getKey() => sprintf('%s · Rate unavailable: %s', $waybill->waybill_number, $exception->getMessage())];
            }
        })->all();
    }

    /**
     * @return array<int, string>
     */
    private function eligibleWorkEntryOptions(): array
    {
        $resolver = app(RateResolverService::class);

        return app(BillingBatchEligibilityService::class)
            ->eligibleWorkEntries($this->currentRecord())
            ->mapWithKeys(function (JobCardWorkEntry $workEntry) use ($resolver): array {
                $jobCard = $workEntry->jobCard;
                $job = $jobCard?->job;

                if ($jobCard === null) {
                    return [];
                }

                $date = filled($jobCard->card_date)
                    ? CarbonImmutable::parse((string) $jobCard->card_date)->format('j M Y')
                    : 'No date';

                $fromState = $workEntry->getAttribute('from_time');
                $toState = $workEntry->getAttribute('to_time');

                $from = blank($fromState)
                    ? 'No start'
                    : CarbonImmutable::parse((string) $fromState)->format('H:i');
                $to = blank($toState)
                    ? 'No end'
                    : CarbonImmutable::parse((string) $toState)->format('H:i');

                try {
                    $resolvedRate = $resolver->resolveForWorkEntry($workEntry);
                } catch (BusinessException $exception) {
                    return [
                        $workEntry->getKey() => sprintf(
                            '%s · %s · %s · %s · Row %s · Rate unavailable: %s',
                            $jobCard->client->display_name ?? $this->currentRecord()->client->display_name ?? 'Client',
                            $job->job_number ?? 'No job',
                            $jobCard->card_number,
                            $date,
                            $workEntry->getKey(),
                            $exception->getMessage(),
                        ),
                    ];
                }
                $hours = round((float) $workEntry->total_hours, 2);
                $rate = round((float) $resolvedRate['rate'], 2);
                $amount = round($hours * $rate, 2);
                $clientName = $jobCard->client->display_name ?? $this->currentRecord()->client->display_name ?? 'Client';
                $agreementReference = $resolvedRate['rate_agreement_reference'];
                $agreementLabel = blank($agreementReference)
                    ? $resolvedRate['rate_agreement_name']
                    : sprintf('%s (%s)', $resolvedRate['rate_agreement_name'], $agreementReference);
                $location = collect([$workEntry->vessel, $workEntry->work_area])->filter()->join(' / ');

                return [
                    $workEntry->getKey() => sprintf(
                        '%s · %s · %s · %s · Row %s · %s-%s · %s hrs · %s · %s · %s @ %s %s/hr = %s %s',
                        $clientName,
                        $job->job_number ?? 'No job',
                        $jobCard->card_number,
                        $date,
                        $workEntry->getKey(),
                        $from,
                        $to,
                        number_format($hours, 2),
                        $jobCard->machine_number ?? $jobCard->equipment_reference ?? 'No equipment',
                        $location !== '' ? $location : 'No vessel/work area',
                        $agreementLabel,
                        $resolvedRate['currency'],
                        number_format($rate, 2),
                        $resolvedRate['currency'],
                        number_format($amount, 2),
                    ),
                ];
            })
            ->all();
    }

    private function currentRecord(): BillingBatch
    {
        if (! $this->record instanceof BillingBatch) {
            throw new \RuntimeException('Expected billing batch record.');
        }

        return $this->record;
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
