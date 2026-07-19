<?php

declare(strict_types=1);

namespace App\Core\Settings\DTOs;

class SettingData
{
    public function __construct(
        public readonly ?int $tenantId,
        public readonly string $group,
        public readonly string $key,
        public readonly mixed $value,
        public readonly bool $isEncrypted = false,
    ) {}
}
