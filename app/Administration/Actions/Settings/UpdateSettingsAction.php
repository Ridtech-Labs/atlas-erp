<?php

declare(strict_types=1);

namespace App\Administration\Actions\Settings;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;

class UpdateSettingsAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly SettingService $settings,
    ) {}

    public function execute(SettingData $settingData, User $actor): void
    {
        if ($settingData->companyId === null
            || ! $actor->can('settings.update')
            || ! $this->access->canAccessActiveOperationalCompany($actor, $settingData->companyId, $settingData->tenantId)) {
            throw new BusinessException('You are not allowed to update settings for this company.', 403);
        }

        $this->settings->update($settingData);
    }
}
