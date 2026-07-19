<?php

declare(strict_types=1);

namespace App\Core\Settings\Actions;

use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;

class UpdateSettingAction
{
    public function __construct(
        private readonly SettingService $settings,
    ) {}

    public function execute(SettingData $settingData): void
    {
        $this->settings->update($settingData);
    }
}
