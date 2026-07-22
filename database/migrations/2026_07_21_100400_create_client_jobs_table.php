<?php

declare(strict_types=1);

use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::create('client_jobs', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('job_number', 50);
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('client_site_id')->nullable()->constrained('client_sites')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 30)->default(JobStatus::Draft->value);
            $table->string('priority', 20)->default(JobPriority::Normal->value);
            $table->date('requested_start_date')->nullable();
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->timestamp('actual_start_date')->nullable();
            $table->timestamp('actual_end_date')->nullable();
            $table->string('client_reference')->nullable();
            $table->string('internal_reference')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'job_number']);
            $table->index(['tenant_id', 'status', 'priority']);
            $table->index(['tenant_id', 'client_id']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE client_jobs ADD CONSTRAINT client_jobs_estimated_value_check CHECK (estimated_value IS NULL OR estimated_value >= 0)');
            DB::statement('ALTER TABLE client_jobs ADD CONSTRAINT client_jobs_planned_dates_check CHECK (planned_start_date IS NULL OR planned_end_date IS NULL OR planned_end_date >= planned_start_date)');
            DB::statement('ALTER TABLE client_jobs ADD CONSTRAINT client_jobs_actual_dates_check CHECK (actual_start_date IS NULL OR actual_end_date IS NULL OR actual_end_date >= actual_start_date)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_jobs');
    }
};
