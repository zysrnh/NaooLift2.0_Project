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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('day_name'); // SENIN, SELASA, RABU, KAMIS, JUMAT, SABTU, MINGGU
            $table->string('title'); // e.g. "PUSH DAY", "PULL DAY", "REST DAY"
            $table->string('focus_target')->nullable(); // e.g. "DADA & TRICEPS"
            $table->text('notes')->nullable(); // e.g. "4 Set Bench Press..."
            $table->boolean('is_rest')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
