<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_asset_assignments', function (Blueprint $table): void {
            $table->timestamp('dispatched_at')->nullable()->index();
            $table->foreignId('dispatched_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('dispatch_notes')->nullable();
            $table->timestamp('returned_at')->nullable()->index();
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('return_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('job_asset_assignments', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('dispatched_by');
            $table->dropConstrainedForeignId('returned_by');
            $table->dropColumn(['dispatched_at', 'dispatch_notes', 'returned_at', 'return_notes']);
        });
    }
};
