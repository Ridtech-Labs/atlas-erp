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
        if (! Schema::hasTable('clients')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table): void {
            if (! Schema::hasColumn('clients', 'trading_name')) {
                $table->string('trading_name')->nullable()->after('legal_name');
            }

            if (! Schema::hasColumn('clients', 'alternate_phone')) {
                $table->string('alternate_phone', 30)->nullable()->after('phone');
            }

            if (! Schema::hasColumn('clients', 'billing_address')) {
                $table->text('billing_address')->nullable()->after('website');
            }

            if (! Schema::hasColumn('clients', 'physical_address')) {
                $table->text('physical_address')->nullable()->after('billing_address');
            }

            if (! Schema::hasColumn('clients', 'city')) {
                $table->string('city', 120)->nullable()->after('physical_address');
            }

            if (! Schema::hasColumn('clients', 'region')) {
                $table->string('region', 120)->nullable()->after('city');
            }

            if (! Schema::hasColumn('clients', 'country')) {
                $table->string('country', 120)->default('Ghana')->after('region');
            }
        });

        if (Schema::hasColumn('clients', 'alternate_phone') && Schema::hasColumn('clients', 'alternative_phone')) {
            DB::table('clients')
                ->whereNull('alternate_phone')
                ->whereNotNull('alternative_phone')
                ->update([
                    'alternate_phone' => DB::raw('alternative_phone'),
                ]);
        }

        if (! $this->hasIndex('clients', 'clients_tenant_id_trading_name_index')) {
            Schema::table('clients', function (Blueprint $table): void {
                $table->index(['tenant_id', 'trading_name'], 'clients_tenant_id_trading_name_index');
            });
        }
    }

    public function down(): void
    {
        // Intentionally left empty to avoid destructive rollback on shared databases.
    }

    private function hasIndex(string $table, string $index): bool
    {
        return match (DB::getDriverName()) {
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
