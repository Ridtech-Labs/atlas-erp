<?php

declare(strict_types=1);

use App\Finance\Enums\BillingSourceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->foreignId('work_entry_id')->nullable()->change();
            $table->foreignId('job_card_id')->nullable()->change();
            $table->string('source_type', 50)->nullable()->after('billing_batch_id');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->string('source_reference')->nullable()->after('source_id');
            $table->decimal('quantity', 12, 2)->nullable()->after('hours');
            $table->string('billing_unit', 20)->nullable()->after('quantity');
            $table->string('pickup_point')->nullable()->after('work_area');
            $table->string('destination')->nullable()->after('pickup_point');
            $table->string('truck_number')->nullable()->after('destination');
            $table->string('driver_name')->nullable()->after('truck_number');
            $table->string('client_reference')->nullable()->after('driver_name');
        });

        DB::table('billing_batch_lines')->whereNull('source_type')->update([
            'source_type' => BillingSourceType::HeavyMachineryWorkEntry->value,
            'source_id' => DB::raw('work_entry_id'),
            'quantity' => DB::raw('hours'),
            'billing_unit' => 'hourly',
        ]);

        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->unique(['source_type', 'source_id'], 'billing_batch_lines_source_unique');
        });
    }

    public function down(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->dropUnique('billing_batch_lines_source_unique');
            $table->dropColumn(['source_type', 'source_id', 'source_reference', 'quantity', 'billing_unit', 'pickup_point', 'destination', 'truck_number', 'driver_name', 'client_reference']);
            $table->foreignId('work_entry_id')->nullable(false)->change();
            $table->foreignId('job_card_id')->nullable(false)->change();
        });
    }
};
