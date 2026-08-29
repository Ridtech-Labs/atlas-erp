<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_batch_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('billing_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_entry_id')->unique()->constrained('job_card_work_entries')->restrictOnDelete();
            $table->foreignId('job_card_id')->constrained('job_cards')->restrictOnDelete();
            $table->foreignId('job_id')->constrained('client_jobs')->restrictOnDelete();
            $table->string('job_reference')->nullable();
            $table->date('activity_date');
            $table->string('equipment_reference')->nullable();
            $table->string('machine_number')->nullable();
            $table->decimal('hours', 8, 2);
            $table->decimal('resolved_rate', 12, 2);
            $table->string('currency', 3);
            $table->decimal('line_amount', 14, 2);
            $table->foreignId('rate_agreement_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rate_agreement_line_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['billing_batch_id', 'activity_date']);
            $table->index(['job_card_id', 'job_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_batch_lines');
    }
};
