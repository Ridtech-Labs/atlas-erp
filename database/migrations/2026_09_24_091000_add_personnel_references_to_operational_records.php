<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_jobs', fn (Blueprint $table) => $table->foreignId('assigned_personnel_id')->nullable()->constrained('personnel')->nullOnDelete()->after('assigned_operator_id'));
        Schema::table('job_asset_assignments', fn (Blueprint $table) => $table->foreignId('personnel_id')->nullable()->constrained('personnel')->nullOnDelete()->after('operator_user_id'));
        Schema::table('job_cards', fn (Blueprint $table) => $table->foreignId('operator_personnel_id')->nullable()->constrained('personnel')->nullOnDelete()->after('operator_id'));
        Schema::table('job_card_operators', fn (Blueprint $table) => $table->foreignId('personnel_id')->nullable()->constrained('personnel')->nullOnDelete()->after('user_id'));
        Schema::table('waybills', fn (Blueprint $table) => $table->foreignId('driver_personnel_id')->nullable()->constrained('personnel')->nullOnDelete()->after('driver_name'));
    }

    public function down(): void
    {
        Schema::table('waybills', fn (Blueprint $table) => $table->dropConstrainedForeignId('driver_personnel_id'));
        Schema::table('job_card_operators', fn (Blueprint $table) => $table->dropConstrainedForeignId('personnel_id'));
        Schema::table('job_cards', fn (Blueprint $table) => $table->dropConstrainedForeignId('operator_personnel_id'));
        Schema::table('job_asset_assignments', fn (Blueprint $table) => $table->dropConstrainedForeignId('personnel_id'));
        Schema::table('client_jobs', fn (Blueprint $table) => $table->dropConstrainedForeignId('assigned_personnel_id'));
    }
};
