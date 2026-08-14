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
        Schema::table('companies', function (Blueprint $table): void {
            if (! Schema::hasColumn('companies', 'email')) {
                $table->string('email')->nullable()->after('legal_name');
            }

            if (! Schema::hasColumn('companies', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }

            if (! Schema::hasColumn('companies', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('companies', 'address')) {
                $table->text('address')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('companies', 'city')) {
                $table->string('city', 120)->nullable()->after('address');
            }

            if (! Schema::hasColumn('companies', 'timezone')) {
                $table->string('timezone', 100)->nullable()->after('city');
            }
        });

        $tenantColumns = collect(['email', 'phone', 'logo_path', 'address', 'city', 'country', 'timezone', 'currency'])
            ->filter(fn (string $column): bool => Schema::hasColumn('tenants', $column))
            ->values();

        if ($tenantColumns->isEmpty()) {
            return;
        }

        DB::table('companies')
            ->where('is_default', true)
            ->orderBy('id')
            ->get(['id', 'tenant_id'])
            ->each(function (object $company) use ($tenantColumns): void {
                $tenant = DB::table('tenants')
                    ->where('id', $company->tenant_id)
                    ->first($tenantColumns->all());

                if ($tenant === null) {
                    return;
                }

                $payload = [];

                foreach ($tenantColumns as $column) {
                    $payload[$column] = data_get($tenant, $column);
                }

                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(array_filter($payload, static fn (mixed $value): bool => $value !== null));
            });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            foreach (['email', 'phone', 'logo_path', 'address', 'city', 'timezone'] as $column) {
                if (Schema::hasColumn('companies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
