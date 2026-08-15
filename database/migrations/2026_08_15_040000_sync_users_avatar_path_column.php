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
        if (! Schema::hasColumn('users', 'avatar_path')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('avatar_path')->nullable();
            });
        }

        if (Schema::hasColumn('users', 'profile_photo_path') && Schema::hasColumn('users', 'avatar_path')) {
            DB::statement('
                update users
                set avatar_path = profile_photo_path
                where avatar_path is null
                  and profile_photo_path is not null
            ');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'avatar_path')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('avatar_path');
            });
        }
    }
};
