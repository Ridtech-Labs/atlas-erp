<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->string('vessel')->nullable()->after('activity_date');
            $table->string('work_area')->nullable()->after('vessel');
            $table->time('from_time')->nullable()->after('work_area');
            $table->time('to_time')->nullable()->after('from_time');
        });
    }

    public function down(): void
    {
        Schema::table('billing_batch_lines', function (Blueprint $table): void {
            $table->dropColumn([
                'vessel',
                'work_area',
                'from_time',
                'to_time',
            ]);
        });
    }
};
