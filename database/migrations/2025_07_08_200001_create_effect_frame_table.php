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
        Schema::create('effect_frame', function (Blueprint $table) {
            $table->id();
            $table->foreignId('effect_id')->constrained()->onDelete('cascade');
            $table->foreignId('frame_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['effect_id', 'frame_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('effect_frame');
    }
};
