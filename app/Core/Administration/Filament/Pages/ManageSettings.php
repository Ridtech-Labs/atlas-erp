<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Actions\Settings\UpdateSettingsAction;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;
use App\Core\Tenancy\Models\Company;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class ManageSettings extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Company Settings';

    protected static ?string $slug = 'settings';

    protected string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $general = [];

    /** @var array<string, mixed> */
    public array $notifications = [];

    public function mount(SettingService $settings): void
    {
        $user = $this->authenticatedUser();
        $company = $this->activeCompany($user);
        $records = $settings->allForCompany($company->tenant_id, $company->getKey())
            ->keyBy(fn ($record) => "{$record->group}.{$record->key}");

        $this->general = $this->arrayValue($records['company.preferences']->value ?? null, [
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'language' => 'en',
        ]);

        $this->notifications = $this->arrayValue($records['notifications.channels']->value ?? null, [
            'email_notifications_enabled' => true,
            'database_notifications_enabled' => true,
        ]);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        $access = app(AdministrationAccessService::class);
        $company = $access->activeCompany($user);

        return ($user?->can('settings.view') ?? false)
            && $company instanceof Company
            && $access->canAccessActiveOperationalCompany($user, $company->getKey(), $company->tenant_id);
    }

    public function save(UpdateSettingsAction $action, AdministrationActivityLogger $logger): void
    {
        $user = $this->authenticatedUser();

        abort_unless($user->can('settings.update'), 403);

        $this->validate([
            'general.date_format' => ['required', 'string', 'max:20'],
            'general.time_format' => ['required', 'string', 'max:20'],
            'general.language' => ['required', 'string', 'max:10'],
            'notifications.email_notifications_enabled' => ['boolean'],
            'notifications.database_notifications_enabled' => ['boolean'],
        ]);

        $company = $this->activeCompany($user);
        $tenantId = $company->tenant_id;
        $companyId = $company->getKey();

        $action->execute(new SettingData($tenantId, 'company', 'preferences', $this->general, companyId: $companyId), $user);
        $action->execute(new SettingData($tenantId, 'notifications', 'channels', $this->notifications, companyId: $companyId), $user);

        $logger->log('settings.updated', 'Settings updated', $user, $company->tenant, [
            'tenant_id' => $tenantId,
            'company_id' => $companyId,
        ]);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $default
     * @return array<string, mixed>
     */
    private function arrayValue(mixed $value, array $default): array
    {
        return is_array($value) ? $value : $default;
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function activeCompany(User $user): Company
    {
        $access = app(AdministrationAccessService::class);
        $company = $access->activeCompany($user);

        abort_unless(
            $company instanceof Company
            && $access->canAccessActiveOperationalCompany($user, $company->getKey(), $company->tenant_id),
            403,
        );

        return $company;
    }
}
