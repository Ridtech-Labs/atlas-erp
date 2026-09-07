<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rate_agreement_lines', function (Blueprint $table): void {
            $table->string('pickup_point')->nullable()->after('machine_number');
            $table->string('destination')->nullable()->after('pickup_point');
            $table->index(['billing_unit', 'pickup_point', 'destination'], 'rate_agreement_lines_route_lookup');
        });
    }

    public function down(): void
    {
        Schema::table('rate_agreement_lines', function (Blueprint $table): void {
            $table->dropIndex('rate_agreement_lines_route_lookup');
            $table->dropColumn(['pickup_point', 'destination']);
        });
    }
};
