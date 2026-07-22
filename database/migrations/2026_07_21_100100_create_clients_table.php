<?php

declare(strict_types=1);

use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients')) {
            return;
        }

        Schema::create('clients', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('client_code', 50);
            $table->string('legal_name');
            $table->string('trading_name')->nullable();
            $table->string('client_type', 30)->default(ClientType::Corporate->value);
            $table->string('status', 30)->default(ClientStatus::Active->value);
            $table->string('tax_identification_number', 100)->nullable();
            $table->string('registration_number', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('alternate_phone', 30)->nullable();
            $table->string('website')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('physical_address')->nullable();
            $table->string('city', 120)->nullable();
            $table->string('region', 120)->nullable();
            $table->string('country', 120)->default('Ghana');
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->unsignedInteger('payment_terms_days')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'client_code']);
            $table->index(['tenant_id', 'status', 'client_type']);
            $table->index(['tenant_id', 'legal_name']);
            $table->index(['tenant_id', 'trading_name']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE clients ADD CONSTRAINT clients_payment_terms_days_check CHECK (payment_terms_days IS NULL OR payment_terms_days >= 0)');
            DB::statement('ALTER TABLE clients ADD CONSTRAINT clients_credit_limit_check CHECK (credit_limit IS NULL OR credit_limit >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
