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
            if (! Schema::hasColumn('client_jobs', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }

            if (! Schema::hasColumn('client_jobs', 'completed_by')) {
                $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('client_jobs', 'completed_at') && Schema::hasColumn('client_jobs', 'actual_end_date')) {
            DB::table('client_jobs')
                ->where('status', 'completed')
                ->whereNull('completed_at')
                ->whereNotNull('actual_end_date')
                ->update([
                    'completed_at' => DB::raw('actual_end_date'),
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            if (Schema::hasColumn('client_jobs', 'completed_by')) {
                $table->dropConstrainedForeignId('completed_by');
            }

            if (Schema::hasColumn('client_jobs', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
};
