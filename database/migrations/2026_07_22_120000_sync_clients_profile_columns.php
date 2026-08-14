<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table): void {
            if (! Schema::hasColumn('clients', 'tax_identification_number')) {
                $table->string('tax_identification_number', 100)->nullable()->after('status');
            }

            if (! Schema::hasColumn('clients', 'registration_number')) {
                $table->string('registration_number', 100)->nullable()->after('tax_identification_number');
            }

            if (! Schema::hasColumn('clients', 'alternate_phone')) {
                $table->string('alternate_phone', 30)->nullable()->after('phone');
            }

            if (! Schema::hasColumn('clients', 'website')) {
                $table->string('website')->nullable()->after('alternate_phone');
            }

            if (! Schema::hasColumn('clients', 'billing_address')) {
                $table->text('billing_address')->nullable()->after('website');
            }

            if (! Schema::hasColumn('clients', 'physical_address')) {
                $table->text('physical_address')->nullable()->after('billing_address');
            }

            if (! Schema::hasColumn('clients', 'city')) {
                $table->string('city', 120)->nullable()->after('physical_address');
            }

            if (! Schema::hasColumn('clients', 'region')) {
                $table->string('region', 120)->nullable()->after('city');
            }

            if (! Schema::hasColumn('clients', 'country')) {
                $table->string('country', 120)->default('Ghana')->after('region');
            }

            if (! Schema::hasColumn('clients', 'credit_limit')) {
                $table->decimal('credit_limit', 15, 2)->nullable()->after('country');
            }

            if (! Schema::hasColumn('clients', 'payment_terms_days')) {
                $table->unsignedInteger('payment_terms_days')->nullable()->after('credit_limit');
            }

            if (! Schema::hasColumn('clients', 'notes')) {
                $table->text('notes')->nullable()->after('payment_terms_days');
            }
        });
    }

    public function down(): void
    {
        // Intentionally left empty to avoid destructive rollback on legacy databases.
    }
};
