<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Services;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\ActiveTenantResolver;
use App\Core\Tenancy\Support\TenantBranding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantBrandingService
{
    private const ATLAS_PRIMARY = '#2B4EFF';

    public function __construct(
        private readonly ActiveTenantResolver $tenantResolver,
    ) {}

    public function forLoginRequest(Request $request): ?TenantBranding
    {
        $slug = $request->query('tenant');

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        $tenant = Tenant::query()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($tenant->isActive(), 404);

        return $this->forTenant($tenant);
    }

    public function forActiveUser(?User $user): ?TenantBranding
    {
        return $this->forTenant($this->tenantResolver->resolveFor($user));
    }

    public function forTenant(?Tenant $tenant): ?TenantBranding
    {
        if (! $tenant instanceof Tenant || ! $tenant->isActive()) {
            return null;
        }

        $primaryColor = $this->validColor($tenant->brand_primary_color)
            ? strtoupper((string) $tenant->brand_primary_color)
            : self::ATLAS_PRIMARY;
        $rgb = $this->rgb($primaryColor);

        return new TenantBranding(
            displayName: filled($tenant->brand_display_name) ? (string) $tenant->brand_display_name : (string) $tenant->name,
            primaryColor: $primaryColor,
            logoUrl: $this->publicAssetUrl($tenant->brand_logo_path, 'logos'),
            loginHeroUrl: $this->publicAssetUrl($tenant->brand_login_hero_path, 'hero'),
            cssVariables: [
                '--atlas-color-action-primary' => $primaryColor,
                '--atlas-color-action-primary-hover' => $this->darken($rgb, 0.16),
                '--atlas-color-action-primary-soft' => $this->soften($rgb, 0.9),
                '--atlas-login-button-background' => $primaryColor,
                '--atlas-login-button-hover' => $this->darken($rgb, 0.16),
                '--atlas-focus-ring' => '0 0 0 4px rgba('.implode(', ', $rgb).', 0.16)',
                '--fi-color-primary-500' => implode(', ', $rgb),
                '--fi-color-primary-600' => implode(', ', $this->darkenRgb($rgb, 0.1)),
                '--fi-color-primary-700' => implode(', ', $this->darkenRgb($rgb, 0.22)),
                '--atlas-tenant-brand-rgb' => implode(', ', $rgb),
            ],
        );
    }

    private function publicAssetUrl(?string $path, string $kind): ?string
    {
        if (! is_string($path)
            || ! preg_match('#^tenant-branding/[a-z0-9-]+/'.$kind.'/.+$#', $path)
            || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    private function validColor(mixed $color): bool
    {
        return is_string($color) && preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1;
    }

    /** @return array{int, int, int} */
    private function rgb(string $hex): array
    {
        return [
            (int) hexdec(substr($hex, 1, 2)),
            (int) hexdec(substr($hex, 3, 2)),
            (int) hexdec(substr($hex, 5, 2)),
        ];
    }

    /** @param array{int, int, int} $rgb */
    private function darken(array $rgb, float $amount): string
    {
        return '#'.implode('', array_map(fn (int $channel): string => str_pad(dechex((int) round($channel * (1 - $amount))), 2, '0', STR_PAD_LEFT), $rgb));
    }

    /** @param array{int, int, int} $rgb
     * @return array{int, int, int}
     */
    private function darkenRgb(array $rgb, float $amount): array
    {
        return array_map(fn (int $channel): int => (int) round($channel * (1 - $amount)), $rgb);
    }

    /** @param array{int, int, int} $rgb */
    private function soften(array $rgb, float $amount): string
    {
        return '#'.implode('', array_map(fn (int $channel): string => str_pad(dechex((int) round($channel + ((255 - $channel) * $amount))), 2, '0', STR_PAD_LEFT), $rgb));
    }
}
