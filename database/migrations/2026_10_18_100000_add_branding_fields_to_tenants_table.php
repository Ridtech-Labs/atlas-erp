<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->string('brand_display_name')->nullable()->after('logo_path');
            $table->string('brand_logo_path')->nullable()->after('brand_display_name');
            $table->string('brand_primary_color', 7)->nullable()->after('brand_logo_path');
            $table->string('brand_login_hero_path')->nullable()->after('brand_primary_color');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->dropColumn([
                'brand_display_name',
                'brand_logo_path',
                'brand_primary_color',
                'brand_login_hero_path',
            ]);
        });
    }
};
