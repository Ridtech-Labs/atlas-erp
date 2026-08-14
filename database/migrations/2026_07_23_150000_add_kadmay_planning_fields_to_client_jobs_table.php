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
            if (! Schema::hasColumn('client_jobs', 'job_reference')) {
                $table->string('job_reference', 100)->nullable()->after('job_number');
            }

            if (! Schema::hasColumn('client_jobs', 'planned_start_time')) {
                $table->time('planned_start_time')->nullable()->after('planned_start_date');
            }

            if (! Schema::hasColumn('client_jobs', 'planned_end_time')) {
                $table->time('planned_end_time')->nullable()->after('planned_end_date');
            }

            if (! Schema::hasColumn('client_jobs', 'vessel')) {
                $table->string('vessel')->nullable()->after('planned_end_time');
            }

            if (! Schema::hasColumn('client_jobs', 'work_area')) {
                $table->string('work_area')->nullable()->after('vessel');
            }

            if (! Schema::hasColumn('client_jobs', 'equipment_requirement')) {
                $table->string('equipment_requirement')->nullable()->after('work_area');
            }

            if (! Schema::hasColumn('client_jobs', 'assigned_operator_id')) {
                $table->foreignId('assigned_operator_id')->nullable()->after('equipment_requirement')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('client_jobs', 'shift')) {
                $table->string('shift', 30)->nullable()->after('assigned_operator_id');
            }
        });

        if (Schema::hasColumn('client_jobs', 'job_reference') && Schema::hasColumn('client_jobs', 'internal_reference')) {
            DB::statement('
                UPDATE client_jobs
                SET job_reference = internal_reference
                WHERE job_reference IS NULL
                  AND internal_reference IS NOT NULL
            ');
        }
    }

    public function down(): void {}
};
