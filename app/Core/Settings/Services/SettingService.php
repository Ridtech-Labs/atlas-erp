<?php

declare(strict_types=1);

namespace App\Core\Settings\Services;

use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use App\Core\Settings\Repositories\SettingRepositoryInterface;
use Illuminate\Support\Collection;

class SettingService
{
    public function __construct(
        private readonly SettingRepositoryInterface $settings,
    ) {}

    /**
     * @return Collection<int, Setting>
     */
    public function allForTenant(?int $tenantId): Collection
    {
        return $this->settings->allForTenant($tenantId);
    }

    public function update(SettingData $settingData): void
    {
        $this->settings->upsert($settingData);
    }
}
