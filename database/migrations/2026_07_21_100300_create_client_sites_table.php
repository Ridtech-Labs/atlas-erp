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
        if (Schema::hasTable('client_sites')) {
            return;
        }

        Schema::create('client_sites', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('site_code', 50);
            $table->string('name');
            $table->text('address');
            $table->string('city', 120)->nullable();
            $table->string('region', 120)->nullable();
            $table->string('country', 120)->default('Ghana');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->text('access_instructions')->nullable();
            $table->text('operational_notes')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'site_code']);
            $table->index(['tenant_id', 'client_id', 'is_active']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX client_sites_primary_per_client ON client_sites (client_id) WHERE is_primary = true');
            DB::statement('ALTER TABLE client_sites ADD CONSTRAINT client_sites_latitude_check CHECK (latitude IS NULL OR (latitude >= -90 AND latitude <= 90))');
            DB::statement('ALTER TABLE client_sites ADD CONSTRAINT client_sites_longitude_check CHECK (longitude IS NULL OR (longitude >= -180 AND longitude <= 180))');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS client_sites_primary_per_client');
        }

        Schema::dropIfExists('client_sites');
    }
};
