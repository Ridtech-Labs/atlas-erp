<?php

declare(strict_types=1);

use App\Operations\Enums\JobCardApprovalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_cards')) {
            return;
        }

        Schema::table('job_cards', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_cards', 'client_card_reference')) {
                $table->string('client_card_reference')->nullable()->after('card_number');
            }

            if (! Schema::hasColumn('job_cards', 'machine_number')) {
                $table->string('machine_number')->nullable()->after('equipment_reference');
            }

            if (! Schema::hasColumn('job_cards', 'from_time')) {
                $table->time('from_time')->nullable()->after('machine_number');
            }

            if (! Schema::hasColumn('job_cards', 'to_time')) {
                $table->time('to_time')->nullable()->after('from_time');
            }

            if (! Schema::hasColumn('job_cards', 'total_hours')) {
                $table->decimal('total_hours', 8, 2)->nullable()->after('header_hours');
            }

            if (! Schema::hasColumn('job_cards', 'is_client_issued')) {
                $table->boolean('is_client_issued')->default(true)->after('total_hours');
            }

            if (! Schema::hasColumn('job_cards', 'client_endorsed')) {
                $table->boolean('client_endorsed')->default(false)->after('is_client_issued');
            }

            if (! Schema::hasColumn('job_cards', 'client_stamped')) {
                $table->boolean('client_stamped')->default(false)->after('client_endorsed');
            }

            if (! Schema::hasColumn('job_cards', 'verification_notes')) {
                $table->text('verification_notes')->nullable()->after('return_reason');
            }

            if (! Schema::hasColumn('job_cards', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('verification_notes')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('job_cards', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }

            if (! Schema::hasColumn('job_cards', 'billing_ready_at')) {
                $table->timestamp('billing_ready_at')->nullable()->after('verified_at');
            }

            if (! Schema::hasColumn('job_cards', 'billing_ready_by')) {
                $table->foreignId('billing_ready_by')->nullable()->after('billing_ready_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('job_cards', 'rate_currency')) {
                $table->string('rate_currency', 3)->nullable()->after('billing_ready_by');
            }

            if (! Schema::hasColumn('job_cards', 'hourly_rate')) {
                $table->decimal('hourly_rate', 12, 2)->nullable()->after('rate_currency');
            }

            if (! Schema::hasColumn('job_cards', 'exchange_rate')) {
                $table->decimal('exchange_rate', 12, 4)->nullable()->after('hourly_rate');
            }

            if (! Schema::hasColumn('job_cards', 'converted_hourly_rate')) {
                $table->decimal('converted_hourly_rate', 12, 2)->nullable()->after('exchange_rate');
            }

            if (! Schema::hasColumn('job_cards', 'billable_amount')) {
                $table->decimal('billable_amount', 14, 2)->nullable()->after('converted_hourly_rate');
            }

            if (! Schema::hasColumn('job_cards', 'rate_notes')) {
                $table->text('rate_notes')->nullable()->after('billable_amount');
            }

            if (! Schema::hasColumn('job_cards', 'legacy_generated')) {
                $table->boolean('legacy_generated')->default(false)->after('rate_notes');
            }
        });

        DB::table('job_cards')
            ->where('approval_status', JobCardApprovalStatus::Draft->value)
            ->update(['legacy_generated' => true]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('job_cards')) {
            return;
        }

        Schema::table('job_cards', function (Blueprint $table): void {
            foreach ([
                'legacy_generated',
                'rate_notes',
                'billable_amount',
                'converted_hourly_rate',
                'exchange_rate',
                'hourly_rate',
                'rate_currency',
                'billing_ready_by',
                'billing_ready_at',
                'verified_at',
                'verified_by',
                'verification_notes',
                'client_stamped',
                'client_endorsed',
                'is_client_issued',
                'total_hours',
                'to_time',
                'from_time',
                'machine_number',
                'client_card_reference',
            ] as $column) {
                if (Schema::hasColumn('job_cards', $column)) {
                    if (in_array($column, ['verified_by', 'billing_ready_by'], true)) {
                        $table->dropConstrainedForeignId($column);
                    } else {
                        $table->dropColumn($column);
                    }
                }
            }
        });
    }
};
