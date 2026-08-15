<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_card_operators')) {
            return;
        }

        Schema::create('job_card_operators', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('operator_name')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['job_card_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_card_operators');
    }
};
