<?php

declare(strict_types=1);

namespace App\Core\Shared\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasPublicUuid
{
    public static function bootHasPublicUuid(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('uuid')) {
                $model->setAttribute('uuid', (string) Str::uuid());
            }
        });
    }
}
