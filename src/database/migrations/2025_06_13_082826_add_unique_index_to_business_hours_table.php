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
        Schema::table('business_hours', function (Blueprint $table) {
            $table->unique(['hospital_id', 'day_of_week', 'time_period'], 'hospital_day_of_week_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->dropUnique(['hospital_day_of_week_period_unique']);
        });
    }
};
