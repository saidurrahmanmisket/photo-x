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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kiosk_id')->constrained()->onDelete('cascade');
            $table->enum('payment_type', ['vendor', 'user'])->default('user');
            $table->decimal('amount', 10, 2);
            $table->integer('print_limit')->nullable(); // For vendor type
            $table->integer('prints_used')->default(0); // Track how many prints used
            $table->enum('status', ['pending', 'paid', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}; 