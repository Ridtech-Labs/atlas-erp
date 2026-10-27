<?php

declare(strict_types=1);

namespace App\Core\Settings\Actions;

use App\Core\Settings\Models\Setting;
use App\Core\Tenancy\Models\Company;
use Illuminate\Support\Arr;

class BackfillLegacyCompanySettingsAction
{
    public function execute(): void
    {
        Company::query()
            ->where('is_default', true)
            ->select(['id', 'tenant_id'])
            ->orderBy('id')
            ->each(function (Company $company): void {
                $this->backfillPreferences($company);
                $this->backfillNotifications($company);
            });
    }

    private function backfillPreferences(Company $company): void
    {
        $legacy = $this->legacySetting($company, 'company', 'profile');
        $preferences = Arr::only($this->legacyValue($legacy), [
            'date_format',
            'time_format',
            'language',
        ]);

        if ($preferences !== []) {
            $this->createIfMissing($company, 'company', 'preferences', $preferences);
        }
    }

    private function backfillNotifications(Company $company): void
    {
        $legacy = $this->legacySetting($company, 'notifications', 'channels');
        $notifications = $this->legacyValue($legacy);

        if ($notifications !== []) {
            $this->createIfMissing($company, 'notifications', 'channels', $notifications);
        }
    }

    private function legacySetting(Company $company, string $group, string $key): ?Setting
    {
        return Setting::withoutGlobalScopes()
            ->where('tenant_id', $company->tenant_id)
            ->whereNull('company_id')
            ->where('group', $group)
            ->where('key', $key)
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function legacyValue(?Setting $setting): array
    {
        $rawValue = $setting?->getRawOriginal('value');

        if (! is_string($rawValue)) {
            return [];
        }

        $value = json_decode($rawValue, true);

        return is_array($value) ? $value : [];
    }

    /**
     * @param  array<string, mixed>  $value
     */
    private function createIfMissing(Company $company, string $group, string $key, array $value): void
    {
        Setting::withoutGlobalScopes()->firstOrCreate([
            'tenant_id' => $company->tenant_id,
            'company_id' => $company->getKey(),
            'group' => $group,
            'key' => $key,
        ], [
            'value' => $value,
            'is_encrypted' => false,
        ]);
    }
}
