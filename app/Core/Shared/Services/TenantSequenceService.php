<?php

declare(strict_types=1);

namespace App\Core\Shared\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantSequenceService
{
    public function nextValue(int $tenantId, string $key): int
    {
        $hasLegacyColumns = Schema::hasColumn('tenant_sequences', 'scope')
            && Schema::hasColumn('tenant_sequences', 'next_number');

        return DB::transaction(function () use ($tenantId, $key, $hasLegacyColumns): int {
            $row = DB::table('tenant_sequences')
                ->where('tenant_id', $tenantId)
                ->where(function ($query) use ($key, $hasLegacyColumns): void {
                    $query->where('key', $key)
                        ->when($hasLegacyColumns, fn ($legacyQuery) => $legacyQuery->orWhere('scope', $key));
                })
                ->lockForUpdate()
                ->first();

            if ($row === null) {
                $payload = [
                    'tenant_id' => $tenantId,
                    'key' => $key,
                    'current_value' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($hasLegacyColumns) {
                    $payload['scope'] = $key;
                    $payload['next_number'] = 1;
                }

                DB::table('tenant_sequences')->insert($payload);

                return 1;
            }

            $nextValue = ((int) $row->current_value) + 1;

            $payload = [
                'current_value' => $nextValue,
                'key' => $key,
                'updated_at' => now(),
            ];

            if ($hasLegacyColumns) {
                $payload['scope'] = $key;
                $payload['next_number'] = $nextValue;
            }

            DB::table('tenant_sequences')
                ->where('id', $row->id)
                ->update($payload);

            return $nextValue;
        });
    }

    public function ensureAtLeast(int $tenantId, string $key, int $minimumValue): void
    {
        if ($minimumValue < 1) {
            return;
        }

        $hasLegacyColumns = Schema::hasColumn('tenant_sequences', 'scope')
            && Schema::hasColumn('tenant_sequences', 'next_number');

        DB::transaction(function () use ($tenantId, $key, $minimumValue, $hasLegacyColumns): void {
            $row = DB::table('tenant_sequences')
                ->where('tenant_id', $tenantId)
                ->where(function ($query) use ($key, $hasLegacyColumns): void {
                    $query->where('key', $key)
                        ->when($hasLegacyColumns, fn ($legacyQuery) => $legacyQuery->orWhere('scope', $key));
                })
                ->lockForUpdate()
                ->first();

            if ($row === null) {
                $payload = [
                    'tenant_id' => $tenantId,
                    'key' => $key,
                    'current_value' => $minimumValue,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($hasLegacyColumns) {
                    $payload['scope'] = $key;
                    $payload['next_number'] = $minimumValue;
                }

                DB::table('tenant_sequences')->insert($payload);

                return;
            }

            if ((int) $row->current_value >= $minimumValue) {
                return;
            }

            $payload = [
                'current_value' => $minimumValue,
                'key' => $key,
                'updated_at' => now(),
            ];

            if ($hasLegacyColumns) {
                $payload['scope'] = $key;
                $payload['next_number'] = $minimumValue;
            }

            DB::table('tenant_sequences')
                ->where('id', $row->id)
                ->update($payload);
        });
    }
}
