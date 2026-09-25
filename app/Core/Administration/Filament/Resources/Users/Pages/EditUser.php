<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Pages;

use App\Administration\Actions\Users\RevokeUserInvitationAction;
use App\Administration\Actions\Users\SendUserInvitationAction;
use App\Administration\Actions\Users\UpdateUserAction;
use App\Core\Administration\Filament\Resources\Users\UserResource;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('resendInvitation')
                ->label('Resend invitation')
                ->icon('heroicon-o-envelope')
                ->visible(fn (): bool => $this->record instanceof User
                    && $this->record->getRawOriginal('status') === UserStatus::Invited->value
                    && $this->authenticatedUser()->can('update', $this->record))
                ->action(function (): void {
                    try {
                        app(SendUserInvitationAction::class)->execute($this->invitationRecord(), $this->authenticatedUser(), true);
                        Notification::make()->success()->title('Invitation resent')->body('A new account setup link has been sent.')->send();
                    } catch (BusinessException $exception) {
                        Notification::make()->danger()->title('Invitation could not be resent')->body($exception->getMessage())->send();
                    }
                }),
            Action::make('revokeInvitation')
                ->label('Revoke invitation')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('The pending account setup link will stop working and the account will be disabled.')
                ->visible(fn (): bool => $this->record instanceof User
                    && $this->record->getRawOriginal('status') === UserStatus::Invited->value
                    && $this->authenticatedUser()->can('update', $this->record))
                ->action(function (): void {
                    try {
                        app(RevokeUserInvitationAction::class)->execute($this->invitationRecord(), $this->authenticatedUser());
                        Notification::make()->success()->title('Invitation revoked')->body('The account setup link is no longer valid.')->send();
                    } catch (BusinessException $exception) {
                        Notification::make()->danger()->title('Invitation could not be revoked')->body($exception->getMessage())->send();
                    }
                }),
            DeleteAction::make()->requiresConfirmation(),
            ForceDeleteAction::make()->requiresConfirmation(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof User) {
            $data['roles'] = $this->record->getRoleNames()->all();
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof User) {
            throw new \RuntimeException('Expected user record.');
        }

        $roleNames = $data['roles'] ?? [];
        unset($data['roles'], $data['password_confirmation']);

        return app(UpdateUserAction::class)->execute($record, $data, $roleNames, $this->authenticatedUser());
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function invitationRecord(): User
    {
        if (! $this->record instanceof User) {
            throw new \RuntimeException('Expected user record.');
        }

        return $this->record;
    }
}
