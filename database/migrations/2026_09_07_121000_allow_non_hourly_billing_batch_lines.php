<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->decimal('hours', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->decimal('hours', 8, 2)->nullable(false)->change();
        });
    }
};
