<?php

declare(strict_types=1);

namespace App\Core\Settings\Repositories;

use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EloquentSettingRepository implements SettingRepositoryInterface
{
    /**
     * @return Collection<int, Setting>
     */
    public function allForTenant(?int $tenantId): Collection
    {
        return Setting::query()
            ->when($tenantId !== null, fn ($query) => $query->where('tenant_id', $tenantId))
            ->orderBy('group')
            ->orderBy('key')
            ->get();
    }

    public function upsert(SettingData $settingData): void
    {
        $setting = Setting::query()->firstOrNew([
            'tenant_id' => $settingData->tenantId,
            'group' => $settingData->group,
            'key' => $settingData->key,
        ]);

        if (! $setting->exists && ! $setting->getAttribute('uuid')) {
            $setting->uuid = (string) Str::uuid();
        }

        $setting->value = $settingData->value;
        $setting->is_encrypted = $settingData->isEncrypted;
        $setting->save();
    }
}
