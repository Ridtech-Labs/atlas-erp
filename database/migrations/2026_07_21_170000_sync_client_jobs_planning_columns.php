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
        if (! Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            if (! Schema::hasColumn('client_jobs', 'planned_start_date')) {
                $table->date('planned_start_date')->nullable()->after('requested_start_date');
            }

            if (! Schema::hasColumn('client_jobs', 'planned_end_date')) {
                $table->date('planned_end_date')->nullable()->after('planned_start_date');
            }

            if (! Schema::hasColumn('client_jobs', 'client_reference')) {
                $table->string('client_reference')->nullable()->after('actual_end_date');
            }

            if (! Schema::hasColumn('client_jobs', 'internal_reference')) {
                $table->string('internal_reference')->nullable()->after('client_reference');
            }

            if (! Schema::hasColumn('client_jobs', 'estimated_value')) {
                $table->decimal('estimated_value', 15, 2)->nullable()->after('internal_reference');
            }
        });

        if (Schema::hasColumn('client_jobs', 'scheduled_start_date') && Schema::hasColumn('client_jobs', 'planned_start_date')) {
            DB::table('client_jobs')
                ->whereNull('planned_start_date')
                ->whereNotNull('scheduled_start_date')
                ->update([
                    'planned_start_date' => DB::raw('scheduled_start_date'),
                ]);
        }

        if (Schema::hasColumn('client_jobs', 'scheduled_end_date') && Schema::hasColumn('client_jobs', 'planned_end_date')) {
            DB::table('client_jobs')
                ->whereNull('planned_end_date')
                ->whereNotNull('scheduled_end_date')
                ->update([
                    'planned_end_date' => DB::raw('scheduled_end_date'),
                ]);
        }

        if (Schema::hasColumn('client_jobs', 'customer_reference') && Schema::hasColumn('client_jobs', 'client_reference')) {
            DB::table('client_jobs')
                ->whereNull('client_reference')
                ->whereNotNull('customer_reference')
                ->update([
                    'client_reference' => DB::raw('customer_reference'),
                ]);
        }

        if (Schema::hasColumn('client_jobs', 'purchase_order_number') && Schema::hasColumn('client_jobs', 'internal_reference')) {
            DB::table('client_jobs')
                ->whereNull('internal_reference')
                ->whereNotNull('purchase_order_number')
                ->update([
                    'internal_reference' => DB::raw('purchase_order_number'),
                ]);
        }

        if (Schema::hasColumn('client_jobs', 'estimated_amount') && Schema::hasColumn('client_jobs', 'estimated_value')) {
            DB::table('client_jobs')
                ->whereNull('estimated_value')
                ->whereNotNull('estimated_amount')
                ->update([
                    'estimated_value' => DB::raw('estimated_amount'),
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            if (Schema::hasColumn('client_jobs', 'estimated_value')) {
                $table->dropColumn('estimated_value');
            }

            if (Schema::hasColumn('client_jobs', 'internal_reference')) {
                $table->dropColumn('internal_reference');
            }

            if (Schema::hasColumn('client_jobs', 'client_reference')) {
                $table->dropColumn('client_reference');
            }

            if (Schema::hasColumn('client_jobs', 'planned_end_date')) {
                $table->dropColumn('planned_end_date');
            }

            if (Schema::hasColumn('client_jobs', 'planned_start_date')) {
                $table->dropColumn('planned_start_date');
            }
        });
    }
};
