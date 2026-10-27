<?php

declare(strict_types=1);

use App\Core\Settings\Actions\BackfillLegacyCompanySettingsAction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dropUnique(['tenant_id', 'group', 'key']);
            $table->unique(['tenant_id', 'company_id', 'group', 'key']);
            $table->index(['tenant_id', 'company_id']);
        });

        app(BackfillLegacyCompanySettingsAction::class)->execute();
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropIndex(['tenant_id', 'company_id']);
            $table->dropUnique(['tenant_id', 'company_id', 'group', 'key']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->unique(['tenant_id', 'group', 'key']);
        });
    }
};
