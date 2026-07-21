<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'last_login_ip')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'last_login_ip')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('last_login_ip');
        });
    }
};
