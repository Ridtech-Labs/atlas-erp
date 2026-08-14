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
        if (Schema::hasTable('client_contacts') && ! Schema::hasColumn('client_contacts', 'company_id')) {
            Schema::table('client_contacts', function (Blueprint $table): void {
                $table->foreignId('company_id')->nullable()->after('tenant_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
                $table->index(['tenant_id', 'company_id'], 'client_contacts_tenant_id_company_id_index');
            });
        }

        if (Schema::hasTable('client_sites') && ! Schema::hasColumn('client_sites', 'company_id')) {
            Schema::table('client_sites', function (Blueprint $table): void {
                $table->foreignId('company_id')->nullable()->after('tenant_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
                $table->index(['tenant_id', 'company_id'], 'client_sites_tenant_id_company_id_index');
            });
        }

        if (Schema::hasColumn('client_contacts', 'company_id')) {
            DB::statement('
                UPDATE client_contacts
                SET company_id = clients.company_id
                FROM clients
                WHERE client_contacts.client_id = clients.id
                  AND client_contacts.company_id IS NULL
            ');
        }

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

    public function down(): void
    {
        if (Schema::hasTable('client_contacts') && Schema::hasColumn('client_contacts', 'company_id')) {
            Schema::table('client_contacts', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropIndex('client_contacts_tenant_id_company_id_index');
                $table->dropColumn('company_id');
            });
        }

        if (Schema::hasTable('client_sites') && Schema::hasColumn('client_sites', 'company_id')) {
            Schema::table('client_sites', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropIndex('client_sites_tenant_id_company_id_index');
                $table->dropColumn('company_id');
            });
        }
    }
};
