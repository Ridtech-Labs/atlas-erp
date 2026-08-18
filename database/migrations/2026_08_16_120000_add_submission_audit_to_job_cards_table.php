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
            if (! Schema::hasColumn('job_cards', 'submitted_by')) {
                $table->foreignId('submitted_by')
                    ->nullable()
                    ->after('approved_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('job_cards', 'submitted_at')) {
                $table->timestamp('submitted_at')
                    ->nullable()
                    ->after('submitted_by');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('job_cards')) {
            return;
        }

        Schema::table('job_cards', function (Blueprint $table): void {
            if (Schema::hasColumn('job_cards', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }

            if (Schema::hasColumn('job_cards', 'submitted_by')) {
                $table->dropConstrainedForeignId('submitted_by');
            }
        });
    }
};
