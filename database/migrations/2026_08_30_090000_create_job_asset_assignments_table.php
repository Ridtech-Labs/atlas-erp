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
        Schema::create('job_asset_assignments', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('job_id')->constrained('client_jobs')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fleet_asset_id')->constrained('fleet_assets')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('operator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('operator_name', 160)->nullable();
            $table->timestamp('planned_start_at');
            $table->timestamp('planned_end_at');
            $table->timestamp('assigned_at');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 24)->default('assigned');
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('release_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'company_id', 'status']);
            $table->index(['job_id', 'status']);
            $table->index(['fleet_asset_id', 'status', 'planned_start_at', 'planned_end_at'], 'job_asset_assignment_conflict_index');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE job_asset_assignments ADD CONSTRAINT job_asset_assignments_valid_window CHECK (planned_end_at > planned_start_at)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_asset_assignments');
    }
};
