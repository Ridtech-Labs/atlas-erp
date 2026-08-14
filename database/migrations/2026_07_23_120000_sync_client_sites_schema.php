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
        if (! Schema::hasTable('client_sites')) {
            return;
        }

        $this->renameLegacyColumns();
        $this->syncStatusColumn();

        Schema::table('client_sites', function (Blueprint $table): void {
            if (! Schema::hasColumn('client_sites', 'address_line_2')) {
                $table->string('address_line_2')->nullable()->after('address_line_1');
            }

            if (! Schema::hasColumn('client_sites', 'postal_code')) {
                $table->string('postal_code', 40)->nullable()->after('country');
            }

            if (! Schema::hasColumn('client_sites', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        if (Schema::hasColumn('client_sites', 'company_id')) {
            DB::statement('
                UPDATE client_sites
                SET company_id = clients.company_id
                FROM clients
                WHERE client_sites.client_id = clients.id
                  AND client_sites.company_id IS NULL
            ');
        }
    }

    public function down(): void {}

    private function renameLegacyColumns(): void
    {
        Schema::table('client_sites', function (Blueprint $table): void {
            if (Schema::hasColumn('client_sites', 'address') && ! Schema::hasColumn('client_sites', 'address_line_1')) {
                $table->renameColumn('address', 'address_line_1');
            }

            if (Schema::hasColumn('client_sites', 'access_instructions') && ! Schema::hasColumn('client_sites', 'directions')) {
                $table->renameColumn('access_instructions', 'directions');
            }

            if (Schema::hasColumn('client_sites', 'operational_notes') && ! Schema::hasColumn('client_sites', 'notes')) {
                $table->renameColumn('operational_notes', 'notes');
            }
        });
    }

    private function syncStatusColumn(): void
    {
        if (! Schema::hasColumn('client_sites', 'status')) {
            Schema::table('client_sites', function (Blueprint $table): void {
                $table->string('status', 20)->default('active')->after('is_primary');
            });
        }

        if (Schema::hasColumn('client_sites', 'is_active')) {
            DB::statement("
                UPDATE client_sites
                SET status = CASE
                    WHEN is_active = true THEN 'active'
                    ELSE 'inactive'
                END
                WHERE status IS NULL
            ");
        } else {
            DB::statement("
                UPDATE client_sites
                SET status = 'active'
                WHERE status IS NULL
            ");
        }

        if (Schema::hasColumn('client_sites', 'status') && DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE client_sites ALTER COLUMN status SET DEFAULT 'active'");
            DB::statement('ALTER TABLE client_sites ALTER COLUMN status SET NOT NULL');
        }

        Schema::table('client_sites', function (Blueprint $table): void {
            if (Schema::hasColumn('client_sites', 'is_active')) {
                if (DB::getDriverName() === 'sqlite') {
                    DB::statement('DROP INDEX IF EXISTS client_sites_tenant_id_client_id_is_active_index');
                }

                $table->dropColumn('is_active');
            }
        });
    }
};
