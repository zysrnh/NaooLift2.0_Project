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
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('log_date'); // Date of workout e.g. 2026-08-11
            $table->string('routine_title'); // Linked routine title e.g. PUSH DAY
            $table->string('exercise_name'); // e.g. BENCH PRESS
            $table->integer('sets')->default(1); // e.g. 4
            $table->integer('reps')->default(10); // e.g. 12
            $table->decimal('weight_kg', 8, 2)->default(0); // e.g. 60.00
            $table->text('notes')->nullable(); // Catatan spesifik latihan pada tanggal tersebut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
