<?php

declare(strict_types=1);

use App\Operations\Enums\JobCardApprovalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_cards')) {
            return;
        }

        Schema::create('job_cards', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('job_id')->constrained('client_jobs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('client_site_id')->nullable()->constrained('client_sites')->cascadeOnUpdate()->nullOnDelete();
            $table->date('card_date');
            $table->string('shift', 30)->nullable();
            $table->string('equipment_reference')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('operated_by')->nullable();
            $table->string('supervising_officer_name')->nullable();
            $table->decimal('header_hours', 8, 2)->nullable();
            $table->text('officer_remarks')->nullable();
            $table->string('approval_status', 30)->default(JobCardApprovalStatus::Draft->value);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'company_id', 'job_id']);
            $table->index(['tenant_id', 'company_id', 'card_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
