<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('uuid')->unique();
            $table->string('group');
            $table->string('key');
            $table->json('value')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'group', 'key']);
            $table->index(['group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
