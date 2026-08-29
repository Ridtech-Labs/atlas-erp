<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Pages;

use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Actions\BillingRecords\CloseBillingRecordAction;
use App\Finance\Actions\BillingRecords\DeleteBillingRecordAction;
use App\Finance\Actions\BillingRecords\IssueBillingRecordAction;
use App\Finance\Actions\BillingRecords\MarkBillingRecordPaidAction;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBillingRecord extends ViewRecord
{
    protected static string $resource = BillingRecordResource::class;

    public function getTitle(): string
    {
        return $this->currentRecord()->record_number;
    }

    public function getSubheading(): ?string
    {
        return 'Internal finance record for the externally issued VAT receipt. Commercial values remain fixed from Billing Batch preparation.';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn (): bool => $this->currentRecord()->status === BillingRecordStatus::Draft),
            Action::make('issue')
                ->label('Record VAT Receipt / Issue')
                ->form([
                    TextInput::make('external_receipt_reference')->label('External VAT receipt reference')->required()->maxLength(255),
                    DatePicker::make('issued_at')->label('Issue date')->required()->native(false),
                    TextInput::make('receipt_amount')->label('VAT receipt amount')->numeric()->required()->minValue(0)->prefix(fn (): string => $this->currentRecord()->currency)->helperText('Must exactly match the system calculated amount shown on this Billing Record.'),
                    Textarea::make('notes')->label('Finance notes')->rows(3)->helperText('Notes support the Finance audit trail and cannot change the commercial amount.'),
                    FileUpload::make('receipt_attachments')
                        ->label('VAT receipt attachment')
                        ->multiple()
                        ->required()
                        ->disk('local')
                        ->directory('billing-record-receipts')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                ])
                ->action(fn (array $data) => $this->issue($data))
                ->visible(fn (): bool => $this->currentRecord()->status === BillingRecordStatus::Draft),
            Action::make('markPaid')
                ->label('Mark Paid')
                ->form([
                    DatePicker::make('paid_at')->label('Payment date')->required()->native(false),
                    TextInput::make('payment_reference')->label('Payment reference')->required()->maxLength(255),
                ])
                ->action(fn (array $data) => $this->markPaid($data))
                ->visible(fn (): bool => $this->currentRecord()->status === BillingRecordStatus::Issued),
            Action::make('close')
                ->label('Close Billing Record')
                ->requiresConfirmation()
                ->action(fn () => $this->close())
                ->visible(fn (): bool => $this->currentRecord()->status === BillingRecordStatus::Paid),
            Action::make('viewReceipt')
                ->label('View VAT Receipt')
                ->url(fn (): ?string => $this->receiptUrl())
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->currentRecord()->getMedia('vat-receipt')->isNotEmpty()),
            Action::make('downloadReceipt')
                ->label('Download VAT Receipt')
                ->url(fn (): ?string => $this->receiptUrl(true))
                ->visible(fn (): bool => $this->currentRecord()->getMedia('vat-receipt')->isNotEmpty()),
            DeleteAction::make()
                ->visible(fn (): bool => $this->currentRecord()->status === BillingRecordStatus::Draft)
                ->using(function (): bool {
                    app(DeleteBillingRecordAction::class)->execute($this->currentRecord(), $this->authenticatedUser());

                    return true;
                }),
        ];
    }

    /** @param array<string, mixed> $data */
    private function issue(array $data): void
    {
        try {
            app(IssueBillingRecordAction::class)->execute(
                $this->currentRecord(),
                $data,
                $this->authenticatedUser(),
                $this->paths($data['receipt_attachments'] ?? []),
            );

            $this->redirect(static::getResource()::getUrl('view', ['record' => $this->currentRecord()]));
        } catch (BusinessException $exception) {
            $this->businessFailure('VAT receipt could not be recorded', $exception);
        }
    }

    /** @param array<string, mixed> $data */
    private function markPaid(array $data): void
    {
        try {
            app(MarkBillingRecordPaidAction::class)->execute($this->currentRecord(), $data, $this->authenticatedUser());
            $this->redirect(static::getResource()::getUrl('view', ['record' => $this->currentRecord()]));
        } catch (BusinessException $exception) {
            $this->businessFailure('Payment could not be recorded', $exception);
        }
    }

    private function close(): void
    {
        try {
            app(CloseBillingRecordAction::class)->execute($this->currentRecord(), $this->authenticatedUser());
            $this->redirect(static::getResource()::getUrl('view', ['record' => $this->currentRecord()]));
        } catch (BusinessException $exception) {
            $this->businessFailure('Billing Record could not be closed', $exception);
        }
    }

    private function receiptUrl(bool $download = false): ?string
    {
        $media = $this->currentRecord()->getFirstMedia('vat-receipt');

        return $media === null ? null : route('atlas.billing-records.receipts.show', [
            'billingRecord' => $this->currentRecord(),
            'media' => $media,
            'download' => $download ? 1 : null,
        ]);
    }

    /** @return list<string> */
    private function paths(mixed $paths): array
    {
        return is_array($paths)
            ? array_values(array_filter($paths, static fn (mixed $path): bool => is_string($path) && $path !== ''))
            : [];
    }

    private function businessFailure(string $title, BusinessException $exception): void
    {
        Notification::make()->danger()->title($title)->body($exception->getMessage())->send();
    }

    private function currentRecord(): BillingRecord
    {
        if (! $this->record instanceof BillingRecord) {
            throw new \RuntimeException('Expected Billing Record.');
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
