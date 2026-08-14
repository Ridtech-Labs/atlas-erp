<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clients') || DB::getDriverName() !== 'pgsql') {
            return;
        }

        if (Schema::hasColumn('clients', 'name') && Schema::hasColumn('clients', 'legal_name')) {
            DB::statement('UPDATE clients SET legal_name = COALESCE(legal_name, name) WHERE legal_name IS NULL');
            DB::statement('ALTER TABLE clients ALTER COLUMN name DROP NOT NULL');
            DB::statement('ALTER TABLE clients ALTER COLUMN legal_name SET NOT NULL');
        }

        if (Schema::hasColumn('clients', 'payment_terms_days')) {
            DB::statement('ALTER TABLE clients ALTER COLUMN payment_terms_days DROP NOT NULL');
            DB::statement('ALTER TABLE clients ALTER COLUMN payment_terms_days DROP DEFAULT');
        }

        if (Schema::hasColumn('clients', 'currency')) {
            DB::statement("UPDATE clients SET currency = COALESCE(currency, 'GHS') WHERE currency IS NULL");
            DB::statement("ALTER TABLE clients ALTER COLUMN currency SET DEFAULT 'GHS'");
        }
    }

    public function down(): void
    {
        // Intentionally left empty to avoid destructive rollback on shared databases.
    }
};
