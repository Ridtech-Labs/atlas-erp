<?php

declare(strict_types=1);

use App\Operations\Enums\WaybillStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('waybills')) {
            return;
        }

        Schema::create('waybills', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('job_id')->constrained('client_jobs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('waybill_number')->nullable();
            $table->string('client_reference')->nullable();
            $table->date('waybill_date');
            $table->string('driver_name');
            $table->string('truck_number');
            $table->unsignedInteger('number_of_trips')->default(1);
            $table->decimal('amount_paid', 14, 2)->nullable();
            $table->string('pickup_point')->nullable();
            $table->string('destination')->nullable();
            $table->decimal('amount_paid_to_driver', 14, 2)->nullable();
            $table->string('signature_name')->nullable();
            $table->string('status', 30)->default(WaybillStatus::Recorded->value);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('return_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'company_id', 'job_id']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waybills');
    }
};
