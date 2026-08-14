<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tenant_sequences')) {
            return;
        }

        Schema::table('tenant_sequences', function (Blueprint $table): void {
            if (! Schema::hasColumn('tenant_sequences', 'key')) {
                $table->string('key', 100)->nullable()->after('tenant_id');
            }

            if (! Schema::hasColumn('tenant_sequences', 'current_value')) {
                $table->unsignedBigInteger('current_value')->nullable()->after('key');
            }
        });

        if (Schema::hasColumn('tenant_sequences', 'scope') && Schema::hasColumn('tenant_sequences', 'key')) {
            DB::table('tenant_sequences')
                ->whereNull('key')
                ->update([
                    'key' => DB::raw('scope'),
                ]);
        }

        if (Schema::hasColumn('tenant_sequences', 'next_number') && Schema::hasColumn('tenant_sequences', 'current_value')) {
            DB::table('tenant_sequences')
                ->whereNull('current_value')
                ->update([
                    'current_value' => DB::raw('next_number'),
                ]);
        }

        DB::statement("UPDATE tenant_sequences SET key = 'default' WHERE key IS NULL");
        DB::statement('UPDATE tenant_sequences SET current_value = 0 WHERE current_value IS NULL');

        if (! $this->hasUniqueIndex('tenant_sequences', 'tenant_sequences_tenant_id_key_unique')) {
            Schema::table('tenant_sequences', function (Blueprint $table): void {
                $table->unique(['tenant_id', 'key'], 'tenant_sequences_tenant_id_key_unique');
            });
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive: legacy deployments may still rely on the synced columns.
    }

    private function hasUniqueIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();

        return match ($connection->getDriverName()) {
            'pgsql' => DB::table('pg_indexes')
                ->where('schemaname', 'public')
                ->where('tablename', $table)
                ->where('indexname', $index)
                ->exists(),
            'sqlite' => DB::table('sqlite_master')
                ->where('type', 'index')
                ->where('tbl_name', $table)
                ->where('name', $index)
                ->exists(),
            default => false,
        };
    }
};
