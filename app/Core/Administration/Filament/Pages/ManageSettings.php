<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Settings\Actions\UpdateSettingAction;
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
    public array $branding = [];

    /** @var array<string, mixed> */
    public array $notifications = [];

    public function mount(SettingService $settings): void
    {
        $user = $this->authenticatedUser();
        $company = app(AdministrationAccessService::class)->activeCompany($user);
        $tenantId = $user->tenant_id;
        $records = $settings->allForTenant($tenantId)->keyBy(fn ($record) => "{$record->group}.{$record->key}");
        $defaultCompanyName = $company instanceof Company
            ? $company->name
            : $user->tenant?->name;

        $this->general = $this->arrayValue($records['company.profile']->value ?? null, [
            'name' => $defaultCompanyName,
            'timezone' => $company?->timezone,
            'currency' => $company?->currency,
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
            'country' => $company?->country,
            'language' => 'en',
        ]);

        $this->branding = $this->arrayValue($records['branding.identity']->value ?? null, [
            'logo' => null,
            'small_logo' => null,
            'primary_brand_preference' => 'amber',
        ]);

        $this->notifications = $this->arrayValue($records['notifications.channels']->value ?? null, [
            'email_notifications_enabled' => true,
            'database_notifications_enabled' => true,
        ]);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return ($user?->can('settings.view') ?? false)
            && ! app(AdministrationAccessService::class)->isPlatformSession($user);
    }

    public function save(UpdateSettingAction $action, AdministrationActivityLogger $logger): void
    {
        $user = $this->authenticatedUser();

        abort_unless($user->can('settings.update'), 403);

        $this->validate([
            'general.name' => ['required', 'string', 'max:255'],
            'general.timezone' => ['required', 'string', 'max:100'],
            'general.currency' => ['required', 'string', 'max:10'],
            'general.date_format' => ['required', 'string', 'max:20'],
            'general.time_format' => ['required', 'string', 'max:20'],
            'general.country' => ['nullable', 'string', 'max:120'],
            'general.language' => ['required', 'string', 'max:10'],
            'branding.primary_brand_preference' => ['nullable', 'string', 'max:50'],
            'notifications.email_notifications_enabled' => ['boolean'],
            'notifications.database_notifications_enabled' => ['boolean'],
        ]);

        $tenantId = $user->tenant_id;

        $action->execute(new SettingData($tenantId, 'company', 'profile', $this->general));
        $action->execute(new SettingData($tenantId, 'branding', 'identity', $this->branding));
        $action->execute(new SettingData($tenantId, 'notifications', 'channels', $this->notifications));

        $logger->log('settings.updated', 'Settings updated', $user, $user->tenant, [
            'tenant_id' => $tenantId,
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

        if (! ($user instanceof User)) {
            abort(403);
        }

        return $user;
    }
}
