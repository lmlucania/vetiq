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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained()->onDelete('cascade')->comment('指名獣医師ID');
            $table->dateTime('appointment_at')->comment('予約時刻');
            $table->timestamps();
        });

        Schema::create('appointment_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->integer('status');
            $table->morphs('modifier');
            $table->text('hospital_memo')->nullable()->comment('病院用メモ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('appointment_status_histories');
    }
};
