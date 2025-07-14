<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->integer('max_clicks')->nullable()->after('activation_code');
            $table->integer('max_capture_seconds')->nullable()->after('max_clicks');
        });
    }

    public function down(): void
    {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->dropColumn(['max_clicks', 'max_capture_seconds']);
        });
    }
}; 