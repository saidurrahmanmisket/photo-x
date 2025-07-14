<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frame_kiosk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('frame_id')->constrained()->onDelete('cascade');
            $table->foreignId('kiosk_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['frame_id', 'kiosk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frame_kiosk');
    }
}; 