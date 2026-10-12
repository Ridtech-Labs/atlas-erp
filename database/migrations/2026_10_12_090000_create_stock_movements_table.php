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
        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('movement_type', 32);
            $table->decimal('quantity', 14, 3);
            $table->decimal('signed_quantity_delta', 14, 3);
            $table->decimal('quantity_before', 14, 3);
            $table->decimal('quantity_after', 14, 3);
            $table->string('reference', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('fleet_asset_id')->nullable()->constrained('fleet_assets')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('job_id')->nullable()->constrained('client_jobs')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('personnel_id')->nullable()->constrained('personnel')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('performed_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestampTz('occurred_at');
            $table->timestamps();

            $table->index(['tenant_id', 'company_id', 'inventory_item_id', 'occurred_at']);
            $table->index(['company_id', 'movement_type']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE stock_movements ADD CONSTRAINT stock_movements_positive_quantity CHECK (quantity > 0 AND quantity_before >= 0 AND quantity_after >= 0)');
            DB::statement('ALTER TABLE stock_movements ADD CONSTRAINT stock_movements_balance_integrity CHECK (quantity_after = quantity_before + signed_quantity_delta)');
            DB::statement("ALTER TABLE stock_movements ADD CONSTRAINT stock_movements_direction_integrity CHECK ((movement_type IN ('opening_balance', 'receipt', 'adjustment_increase') AND signed_quantity_delta > 0) OR (movement_type IN ('issue', 'adjustment_decrease') AND signed_quantity_delta < 0))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
