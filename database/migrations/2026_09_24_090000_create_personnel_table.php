<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('company_id')->constrained('companies')->restrictOnDelete();
            $table->string('first_name', 120);
            $table->string('last_name', 120)->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('can_operate_equipment')->default(false);
            $table->boolean('can_drive')->default(false);
            $table->string('status', 20)->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['tenant_id', 'company_id', 'status']);
            $table->index(['company_id', 'can_operate_equipment', 'status']);
            $table->index(['company_id', 'can_drive', 'status']);
            $table->unique(['company_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
