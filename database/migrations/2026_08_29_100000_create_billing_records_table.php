<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_records', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_batch_id')->unique()->constrained()->restrictOnDelete();
            $table->string('record_number')->unique();
            $table->string('status', 32)->default('draft');
            $table->decimal('batch_amount', 14, 2);
            $table->decimal('receipt_amount', 14, 2)->nullable();
            $table->string('currency', 3);
            $table->string('external_receipt_reference')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'company_id', 'external_receipt_reference'], 'billing_records_receipt_reference_unique');
            $table->index(['tenant_id', 'company_id', 'client_id']);
            $table->index(['company_id', 'status']);
            $table->index('issued_at');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_records');
    }
};
