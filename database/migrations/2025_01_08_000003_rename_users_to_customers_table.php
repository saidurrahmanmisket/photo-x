<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('users', 'customers');
        
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['password', 'role', 'provider', 'provider_id', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('password')->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->rememberToken();
        });
        
        Schema::rename('customers', 'users');
    }
}; 