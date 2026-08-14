<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_card_work_entries')) {
            return;
        }

        Schema::create('job_card_work_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('vessel')->nullable();
            $table->string('work_area')->nullable();
            $table->time('from_time');
            $table->time('to_time');
            $table->decimal('normal_hours', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->string('officer_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['job_card_id', 'from_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_card_work_entries');
    }
};
