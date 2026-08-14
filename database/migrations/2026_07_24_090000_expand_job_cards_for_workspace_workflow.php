<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_cards')) {
            return;
        }

        Schema::table('job_cards', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_cards', 'card_number')) {
                $table->string('card_number', 50)->nullable()->after('uuid');
            }

            if (! Schema::hasColumn('job_cards', 'returned_by')) {
                $table->foreignId('returned_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('job_cards', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('returned_by');
            }

            if (! Schema::hasColumn('job_cards', 'return_reason')) {
                $table->text('return_reason')->nullable()->after('returned_at');
            }
        });

        Schema::table('job_cards', function (Blueprint $table): void {
            $table->index(['job_id', 'card_date'], 'job_cards_job_id_card_date_index');
            $table->index(['company_id', 'approval_status'], 'job_cards_company_id_approval_status_index');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('job_cards')) {
            return;
        }

        Schema::table('job_cards', function (Blueprint $table): void {
            if (Schema::hasColumn('job_cards', 'return_reason')) {
                $table->dropColumn('return_reason');
            }

            if (Schema::hasColumn('job_cards', 'returned_at')) {
                $table->dropColumn('returned_at');
            }

            if (Schema::hasColumn('job_cards', 'returned_by')) {
                $table->dropConstrainedForeignId('returned_by');
            }

            if (Schema::hasColumn('job_cards', 'card_number')) {
                $table->dropColumn('card_number');
            }

            $table->dropIndex('job_cards_job_id_card_date_index');
            $table->dropIndex('job_cards_company_id_approval_status_index');
        });
    }
};
