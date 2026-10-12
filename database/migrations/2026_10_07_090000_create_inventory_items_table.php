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
        Schema::create('inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code', 100);
            $table->string('name', 180);
            $table->string('category', 120);
            $table->string('unit_of_measure', 24);
            $table->text('description')->nullable();
            $table->decimal('minimum_stock_level', 14, 3)->default(0);
            $table->decimal('quantity_on_hand', 14, 3)->default(0);
            $table->string('status', 24)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code']);
            $table->index(['tenant_id', 'company_id', 'status']);
            $table->index(['company_id', 'quantity_on_hand', 'minimum_stock_level']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE inventory_items ADD CONSTRAINT inventory_items_non_negative_stock CHECK (quantity_on_hand >= 0 AND minimum_stock_level >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
