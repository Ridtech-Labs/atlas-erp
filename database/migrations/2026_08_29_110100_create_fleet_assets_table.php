<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleet_assets', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('fleet_asset_type_id')->constrained('fleet_asset_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('asset_number', 100);
            $table->string('registration_number', 100)->nullable();
            $table->string('make', 120)->nullable();
            $table->string('model', 120)->nullable();
            $table->string('serial_number', 120)->nullable();
            $table->string('operational_status', 40)->default('available');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'asset_number']);
            $table->unique(['company_id', 'registration_number']);
            $table->index(['tenant_id', 'company_id', 'operational_status']);
            $table->index(['company_id', 'fleet_asset_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_assets');
    }
};
