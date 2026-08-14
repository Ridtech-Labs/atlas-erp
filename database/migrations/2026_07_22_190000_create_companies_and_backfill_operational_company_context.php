<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table): void {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnUpdate()->cascadeOnDelete();
                $table->string('name');
                $table->string('legal_name')->nullable();
                $table->string('code', 50)->nullable();
                $table->string('status', 30)->default('active');
                $table->string('currency', 10)->nullable();
                $table->string('country', 120)->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'status']);
                $table->unique(['tenant_id', 'code']);
            });
        }

        if (! Schema::hasTable('company_user')) {
            Schema::create('company_user', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['company_id', 'user_id']);
                $table->index(['user_id', 'company_id']);
            });
        }

        if (Schema::hasTable('clients') && ! Schema::hasColumn('clients', 'company_id')) {
            Schema::table('clients', function (Blueprint $table): void {
                $table->foreignId('company_id')->nullable()->after('tenant_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
                $table->index(['tenant_id', 'company_id'], 'clients_tenant_id_company_id_index');
            });
        }

        if (Schema::hasTable('client_jobs') && ! Schema::hasColumn('client_jobs', 'company_id')) {
            Schema::table('client_jobs', function (Blueprint $table): void {
                $table->foreignId('company_id')->nullable()->after('tenant_id')->constrained('companies')->cascadeOnUpdate()->restrictOnDelete();
                $table->index(['tenant_id', 'company_id'], 'client_jobs_tenant_id_company_id_index');
            });
        }

        DB::table('tenants')
            ->orderBy('id')
            ->get()
            ->each(function (object $tenant): void {
                $existingCompanyId = DB::table('companies')
                    ->where('tenant_id', $tenant->id)
                    ->where('is_default', true)
                    ->value('id');

                if (! $existingCompanyId) {
                    $existingCompanyId = DB::table('companies')->insertGetId([
                        'uuid' => (string) Str::uuid(),
                        'tenant_id' => $tenant->id,
                        'name' => $tenant->name,
                        'legal_name' => $tenant->name,
                        'code' => $this->defaultCodeForTenant((string) $tenant->name, (int) $tenant->id),
                        'status' => data_get($tenant, 'status', 'active'),
                        'currency' => data_get($tenant, 'currency', 'GHS'),
                        'country' => data_get($tenant, 'country'),
                        'is_default' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'deleted_at' => null,
                    ]);
                }

                DB::table('users')
                    ->where('tenant_id', $tenant->id)
                    ->orderBy('id')
                    ->get()
                    ->each(function (object $user) use ($existingCompanyId): void {
                        $exists = DB::table('company_user')
                            ->where('company_id', $existingCompanyId)
                            ->where('user_id', $user->id)
                            ->exists();

                        if (! $exists) {
                            DB::table('company_user')->insert([
                                'company_id' => $existingCompanyId,
                                'user_id' => $user->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    });

                if (Schema::hasColumn('clients', 'company_id')) {
                    DB::table('clients')
                        ->where('tenant_id', $tenant->id)
                        ->whereNull('company_id')
                        ->update(['company_id' => $existingCompanyId]);
                }

                if (Schema::hasColumn('client_jobs', 'company_id')) {
                    DB::table('client_jobs')
                        ->where('tenant_id', $tenant->id)
                        ->whereNull('company_id')
                        ->update(['company_id' => $existingCompanyId]);
                }
            });
    }

    public function down(): void
    {
        if (Schema::hasTable('client_jobs') && Schema::hasColumn('client_jobs', 'company_id')) {
            Schema::table('client_jobs', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropIndex('client_jobs_tenant_id_company_id_index');
                $table->dropColumn('company_id');
            });
        }

        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'company_id')) {
            Schema::table('clients', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropIndex('clients_tenant_id_company_id_index');
                $table->dropColumn('company_id');
            });
        }

        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }

    private function defaultCodeForTenant(string $name, int $tenantId): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 8));

        if ($prefix === '') {
            $prefix = 'COMPANY';
        }

        return sprintf('%s-%03d', $prefix, $tenantId);
    }
};
