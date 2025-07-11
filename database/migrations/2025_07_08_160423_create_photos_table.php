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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->unsignedBigInteger('kiosk_id');
            $table->unsignedBigInteger('frame_id');
            $table->unsignedBigInteger('effect_id')->nullable();
            $table->timestamps();
            $table->foreign('kiosk_id')->references('id')->on('kiosks')->cascadeOnDelete();
            $table->foreign('frame_id')->references('id')->on('frames')->cascadeOnDelete();
            $table->foreign('effect_id')->references('id')->on('effects')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
