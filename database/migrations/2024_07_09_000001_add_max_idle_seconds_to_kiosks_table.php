<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->integer('max_idle_seconds')->nullable()->after('max_capture_seconds');
        });
    }
    public function down(): void {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->dropColumn('max_idle_seconds');
        });
    }
}; 