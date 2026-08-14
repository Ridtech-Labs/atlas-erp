<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_jobs') || Schema::hasColumn('client_jobs', 'assigned_operator_name')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            $table->string('assigned_operator_name')->nullable()->after('assigned_operator_id');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_jobs') || ! Schema::hasColumn('client_jobs', 'assigned_operator_name')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            $table->dropColumn('assigned_operator_name');
        });
    }
};
