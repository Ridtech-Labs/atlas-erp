<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_jobs')) {
            return;
        }

        Schema::table('client_jobs', function (Blueprint $table): void {
            if (! Schema::hasColumn('client_jobs', 'job_type')) {
                $table->string('job_type')->nullable()->after('description');
            }

            if (! Schema::hasColumn('client_jobs', 'customer_reference')) {
                $table->string('customer_reference')->nullable()->after('status');
            }

            if (! Schema::hasColumn('client_jobs', 'purchase_order_number')) {
                $table->string('purchase_order_number')->nullable()->after('customer_reference');
            }

            if (! Schema::hasColumn('client_jobs', 'scheduled_start_date')) {
                $table->date('scheduled_start_date')->nullable()->after('requested_start_date');
            }

            if (! Schema::hasColumn('client_jobs', 'scheduled_end_date')) {
                $table->date('scheduled_end_date')->nullable()->after('scheduled_start_date');
            }

            if (! Schema::hasColumn('client_jobs', 'currency')) {
                $table->string('currency', 10)->nullable()->after('actual_end_date');
            }

            if (! Schema::hasColumn('client_jobs', 'estimated_amount')) {
                $table->decimal('estimated_amount', 15, 2)->nullable()->after('currency');
            }

            if (! Schema::hasColumn('client_jobs', 'assigned_to')) {
                $table->foreignId('assigned_to')->nullable()->after('estimated_amount')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('client_jobs', 'notes')) {
                $table->text('notes')->nullable()->after('cancellation_reason');
            }
        });
    }

    public function down(): void {}
};
