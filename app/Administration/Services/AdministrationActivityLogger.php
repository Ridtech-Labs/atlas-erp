<?php

declare(strict_types=1);

namespace App\Administration\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdministrationActivityLogger
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function log(string $event, string $description, ?User $actor = null, ?Model $subject = null, array $properties = []): void
    {
        $tenantId = $properties['tenant_id'] ?? ($actor !== null ? $actor->tenant_id : $subject?->getAttribute('tenant_id'));

        activity('administration')
            ->causedBy($actor)
            ->performedOn($subject)
            ->withProperties(array_filter([
                ...$properties,
                'tenant_id' => $tenantId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], static fn (mixed $value): bool => $value !== null))
            ->event($event)
            ->log($description);
    }
}
