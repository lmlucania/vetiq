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
            $table->dropColumn('time_period');
        });

        Schema::table('exception_hours', function (Blueprint $table) {
            $table->dropColumn('time_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->integer('time_period')->nullable();
        });

        Schema::table('exception_hours', function (Blueprint $table) {
            $table->integer('time_period')->nullable();
        });
    }
};
