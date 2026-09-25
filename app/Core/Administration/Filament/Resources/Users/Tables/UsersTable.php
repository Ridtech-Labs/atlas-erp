<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Tables;

use App\Administration\Actions\Users\RevokeUserInvitationAction;
use App\Administration\Actions\Users\SendUserInvitationAction;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable(['first_name', 'last_name'])->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('roles.name')->label('Role(s)')->badge()->separator(', '),
                TextColumn::make('companies.name')->label('Company')->badge()->separator(', ')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (UserStatus $state): string => $state->label())
                    ->color(fn (UserStatus $state): string => $state->color()),
                TextColumn::make('last_login_at')->since()->label('Last login'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(UserStatus::cases())->mapWithKeys(fn (UserStatus $status) => [$status->value => $status->label()])->all()),
                TrashedFilter::make(),
            ])
            ->defaultSort('first_name')
            ->searchPlaceholder('Search users by name, email, or company')
            ->emptyStateHeading('No users yet')
            ->emptyStateDescription('Invite your first team member to start collaborating inside this workspace.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('resendInvitation')
                    ->label('Resend invitation')
                    ->icon('heroicon-o-envelope')
                    ->visible(fn (User $record): bool => self::canManageInvitation($record))
                    ->action(function (User $record): void {
                        try {
                            app(SendUserInvitationAction::class)->execute($record, self::authenticatedUser(), true);
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
                    ->visible(fn (User $record): bool => self::canManageInvitation($record))
                    ->action(function (User $record): void {
                        try {
                            app(RevokeUserInvitationAction::class)->execute($record, self::authenticatedUser());
                            Notification::make()->success()->title('Invitation revoked')->body('The account setup link is no longer valid.')->send();
                        } catch (BusinessException $exception) {
                            Notification::make()->danger()->title('Invitation could not be revoked')->body($exception->getMessage())->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                    ForceDeleteBulkAction::make()->requiresConfirmation(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }

    private static function canManageInvitation(User $record): bool
    {
        $actor = auth()->user();

        return $actor instanceof User
            && $record->getRawOriginal('status') === UserStatus::Invited->value
            && $actor->can('update', $record);
    }

    private static function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
