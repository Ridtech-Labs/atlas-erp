<?php

declare(strict_types=1);

namespace App\Core\Shared\Services;

use Illuminate\Support\Facades\DB;

class TenantSequenceService
{
    public function nextValue(int $tenantId, string $key): int
    {
        return DB::transaction(function () use ($tenantId, $key): int {
            $row = DB::table('tenant_sequences')
                ->where('tenant_id', $tenantId)
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if ($row === null) {
                DB::table('tenant_sequences')->insert([
                    'tenant_id' => $tenantId,
                    'key' => $key,
                    'current_value' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return 1;
            }

            $nextValue = ((int) $row->current_value) + 1;

            DB::table('tenant_sequences')
                ->where('id', $row->id)
                ->update([
                    'current_value' => $nextValue,
                    'updated_at' => now(),
                ]);

            return $nextValue;
        });
    }
}
