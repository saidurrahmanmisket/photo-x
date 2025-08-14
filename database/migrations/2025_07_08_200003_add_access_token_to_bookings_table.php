<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('access_token')->nullable()->after('notes');
            $table->timestamp('last_accessed_at')->nullable()->after('access_token');
        });
    }
    public function down(): void {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['access_token', 'last_accessed_at']);
        });
    }
}; 