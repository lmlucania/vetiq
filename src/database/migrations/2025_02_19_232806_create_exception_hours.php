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
        Schema::create('exception_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade')->comment('病院ID');
            $table->date('date')->comment('受付時間を変更する日付');
            $table->time('start_time')->nullable()->comment('受付開始時間');
            $table->time('end_time')->nullable()->comment('受付終了時間');
            $table->boolean('is_closed')->comment('休診フラグ');
            $table->text('reason')->nullable()->comment('理由');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exception_hours');
    }
};
