<?php

declare(strict_types=1);

namespace App\Core\Settings\Repositories;

use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use Illuminate\Support\Collection;

interface SettingRepositoryInterface
{
    /**
     * @return Collection<int, Setting>
     */
    public function allForTenant(?int $tenantId): Collection;

    /**
     * @return Collection<int, Setting>
     */
    public function allForCompany(int $tenantId, int $companyId): Collection;

    public function upsert(SettingData $settingData): void;
}
