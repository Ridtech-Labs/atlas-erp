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
        if (! Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            if (! Schema::hasColumn('client_jobs', 'created_by')) {
                $table->foreignId('created_by')
                    ->nullable()
                    ->after('shift')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('client_jobs', 'updated_by')) {
                $table->foreignId('updated_by')
                    ->nullable()
                    ->after('created_by')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasColumn('client_jobs', 'created_by') && Schema::hasColumn('client_jobs', 'updated_by')) {
            DB::table('client_jobs')
                ->whereNull('updated_by')
                ->whereNotNull('created_by')
                ->update([
                    'updated_by' => DB::raw('created_by'),
                ]);
        }
    }

    public function down(): void {}
};
