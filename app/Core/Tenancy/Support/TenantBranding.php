<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Support;

final readonly class TenantBranding
{
    /**
     * @param  array<string, string>  $cssVariables
     */
    public function __construct(
        public string $displayName,
        public string $primaryColor,
        public ?string $logoUrl,
        public ?string $loginHeroUrl,
        public array $cssVariables,
    ) {}
}
