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
        Schema::create('vets', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('公開用ID');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade')->comment('病院ID');
            $table->string('last_name')->comment('名前（姓）');
            $table->string('first_name')->comment('名前（名）');
            $table->boolean('accept_appointment')->comment('指名予約可否フラグ');
            $table->text('remark')->comment('備考');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vets');
    }
};
