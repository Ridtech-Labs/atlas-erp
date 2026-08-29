<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_agreement_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rate_agreement_id')->constrained()->cascadeOnDelete();
            $table->string('equipment_reference')->nullable();
            $table->string('machine_number')->nullable();
            $table->string('billing_unit', 32)->default('hourly');
            $table->string('currency', 3);
            $table->decimal('rate', 12, 2);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index(['rate_agreement_id', 'billing_unit']);
            $table->index(['equipment_reference', 'machine_number']);
            $table->index(['effective_from', 'effective_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_agreement_lines');
    }
};
