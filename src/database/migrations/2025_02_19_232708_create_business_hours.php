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
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('公開用ID');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade')->comment('病院ID');
            $table->tinyInteger('day_of_week')->comment('曜日');
            $table->time('start_time')->comment('受付開始時間');
            $table->time('end_time')->comment('受付終了時間');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
