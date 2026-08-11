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
        if (!Schema::hasColumn('workout_logs', 'duration_seconds')) {
            Schema::table('workout_logs', function (Blueprint $table) {
                $table->integer('duration_seconds')->nullable()->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('workout_logs', 'duration_seconds')) {
            Schema::table('workout_logs', function (Blueprint $table) {
                $table->dropColumn('duration_seconds');
            });
        }
    }
};
