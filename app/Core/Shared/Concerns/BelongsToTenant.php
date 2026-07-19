<?php

declare(strict_types=1);

namespace App\Core\Shared\Concerns;

use App\Core\Tenancy\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            $tenantId = app(TenantContext::class)->id();

            if ($tenantId !== null && ! $model->getAttribute('tenant_id')) {
                $model->setAttribute('tenant_id', $tenantId);
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = app(TenantContext::class)->id();

            if ($tenantId !== null) {
                $builder->where(
                    $builder->getModel()->qualifyColumn('tenant_id'),
                    $tenantId,
                );
            }
        });
    }
}
